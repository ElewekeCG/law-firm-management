<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Appointments::query();

        // filter based on user role
        if($user->isLawyer()) {
            $query->where('lawyerId', $user->id);
        } elseif ($user->isClient()) {
            $query->where('clientId', $user->id);
        }

        // optional date range filtering
        if($request->has('start') && $request->has('end')) {
            $query->whereBetween('startTime', [
                Carbon::parse($request->start),
                Carbon::parse($request->end)
            ]);
        }

        // type filtering
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Optional status filtering
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Eager load related models
        $appointments = $query->with(['lawyer', 'client', 'legalCase'])
            ->orderBy('startTime', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments', 'user'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'lawyerId' => 'required|exists:users,id',
                'clientId' => 'nullable|exists:users,id',
                'caseId' => 'nullable|exists:cases,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'startTime' => 'required|date',
                'endTime' => 'required|date|after:startTime',
                'type' => 'required|in:consultation,case_meeting,court_appearance',
                'status' => 'in:scheduled,confirmed,completed,cancelled',
                'location' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            // prevent double booking
            $conflictingAppointment = Appointments::where('lawyerId', $validatedData['lawyerId'])
                ->where(function($query) use ($validatedData) {
                    $query->where(function($q) use($validatedData) {
                        // check if new appointment starts during existing appointment
                        $q->where('startTime', '<=', $validatedData['startTime'])
                            ->where('endTime', '>=', $validatedData['startTime']);
                    })->orWhere(function($q) use ($validatedData) {
                        // check if new appointment ends during existing appointment
                        $q->where('startTime', '<=', $validatedData['endTime'])
                              ->where('endTime', '>=', $validatedData['endTime']);
                    })->orWhere(function($q) use ($validatedData) {
                        // Check if new appointment completely encompasses an existing appointment
                        $q->where('startTime', '>=', $validatedData['startTime'])
                          ->where('endTime', '<=', $validatedData['endTime']);
                    });
                })
                ->first();

                if ($conflictingAppointment) {
                    throw ValidationException::withMessages([
                        'startTime' => ['This time slot is already booked']
                    ]);
                }

                // set client id if not provided
                if(!isset($validatedData['clientId'])) {
                    $validatedData['clientId'] = auth()->id();
                }

                $appointment = Appointments::create($validatedData);
                DB::commit();

                return redirect()->route('appointments.index')
                    ->with('success', 'Appointment scheduled successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Appointment creation failed'. $e->getMessage());

            return back()->with('error', 'Unable to schedule appointment');

        }

    }

    public function availableSlots(Request $request) {
        // Validate inputs
        $request->validate([
            'lawyerId' => 'required|exists:users,id',
            'date' => 'required|date'
        ]);

        $lawyer = User::findOrFail($request->input('lawyerId'));
        $date = Carbon::parse($request->input('date', now()));

        // business hours
        $openingTime = $date->copy()->setHour(8)->setMinute(0);
        $closingTime = $date->copy()->setHour(17)->setMinute(0);

        // Existing appointments
        $appointments = Appointments::where('lawyerId', $lawyer->id)
            ->whereBetween('startTime', [$openingTime, $closingTime])
            ->get();

        // Generate available slots
        $slots = collect();
        $current = $openingTime->copy();

        while ($current->lt($closingTime)) {
            $slotEnd = $current->copy()->addMinutes(30);

            $isAvailable = $appointments->every(function($appointment) use ($current, $slotEnd) {
                return !($current->between($appointment->startTime, $appointment->endTime) ||
                        $slotEnd->between($appointment->startTime, $appointment->endTime));
            });

            $slots->push([
                'start' => $current->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'available' => $isAvailable,
                'startTime' => $current->format('Y-m-d H:i:s'),
                'endTime' => $slotEnd->format('Y-m-d H:i:s')
            ]);

            $current = $slotEnd;
        }

        return response()->json($slots);
    }

    public function create()
    {
        $lawyers = User::lawyers()->get();
        return view('apppointments.modal', compact('lawyers'));
    }

    public function show(Appointments $appointment)
    {
        // authorize access based on user role
        $this->authorize('view', $appointment);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointments $appointment)
    {
        // authorize edit access
        $this->authorize('update', $appointment);

        $lawyers = User::lawyers()->get();
        return view('appointments.edit', compact('appointment', 'lawyers'));
    }

    public function update(Request $request, Appointments $appointment)
    {
        // authorize update
        $this->authorize('update', $appointment);

        $validatedData = $request->validate([
            'lawyerId' => 'sometimes|exists:users,id',
            'clientId' => 'nullable|exists:users,id',
            'caseId' => 'nullable|exists:cases,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'startTime' => 'sometimes|date',
            'endTime' => 'sometimes|date|after:startTime',
            'type' => 'sometimes|in:consultation,case_meeting,court_appearance',
            'status' => 'in:scheduled,confirmed,completed,cancelled',
            'location' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $appointment->update($validatedData);

        return redirect()->route('appointments.view')
            ->with('success', 'Appointment updated successfully');
    }

    public function destroy(Appointments $appointment)
    {
        // Authorize deletion
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully');
    }
}
