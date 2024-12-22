<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Available_slots;
use App\Models\User;
use App\Notifications\cancelledAppt;
use App\Notifications\newAppointment;
use App\Notifications\updatedAppt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cases;
use Illuminate\Validation\ValidationException;
use Log;
use Notification;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isLawyer()) {
            $appointments = Appointments::where('lawyerId', $user->id)
                ->with('client')
                ->orderBy('startTime', 'desc')
                ->paginate(10);
        } elseif ($user->isClient()) {
            $appointments = Appointments::where('clientId', $user->id)
                ->with('lawyer')
                ->orderBy('startTime', 'desc')
                ->paginate(10);
        } elseif ($user->isClerk()) {
            $appointments = Appointments::with(['lawyer', 'client'])
                ->orderBy('startTime', 'desc')
                ->paginate(10);
        } else {
            // Unauthorized access
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        return view('appointments.index', compact('appointments', 'user'));
    }

    public function showOne(Appointments $appointment, $id)
    {
        // $this->authorize('view', $appointment);

        $appointment->load(['lawyer', 'client']);
        $singleAppointment = $appointment::findOrFail($id);

        return view('appointments.show', compact('singleAppointment'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $currentUser = auth()->user();

            $validatedData = $request->validate([
                'lawyerId' => 'required|exists:users,id',
                'clientId' => 'nullable|exists:users,id',
                'availableSlotId' => 'required|exists:available_slots,id',
                'caseId' => 'nullable|exists:cases,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:consultation,case_meeting,court_appearance',
                'status' => 'in:scheduled,confirmed,completed,cancelled',
                'location' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            $availableSlot = Available_slots::where('id', $validatedData['availableSlotId'])
                ->where('status', 'available')
                ->firstorFail();

            if (Carbon::parse($availableSlot->startTime)->isPast()) {
                throw ValidationException::withMessages([
                    'availableSlotId' => ['you cannot select a date in the past']
                ]);
            }

            $clientId = null;
            if ($currentUser->isClient()) {
                $clientId = $currentUser->id;
            } elseif (isset($validatedData['clientId'])) {
                $clientId = $validatedData['clientId'];
            } else {
                $clientId = $currentUser->id;
            }

            // check booking permissions
            $this->validateBookingPermissions($currentUser, $validatedData['lawyerId'], $clientId);

            // prevent double booking
            $conflictingAppointment = Appointments::where('lawyerId', $validatedData['lawyerId'])
                ->where(function ($query) use ($availableSlot) {
                    $query->whereBetween('startTime', [$availableSlot->startTime, $availableSlot->endTime])
                        ->orWhereBetween('endTime', [$availableSlot->startTime, $availableSlot->endTime])
                        ->orWhere(function ($q) use ($availableSlot) {
                            $q->where('startTime', '<=', $availableSlot->startTime)
                                ->where('endTime', '>=', $availableSlot->endTime);
                        });
                })
                ->exists();

            if ($conflictingAppointment) {
                throw ValidationException::withMessages([
                    'startTime' => ['This time slot is already booked']
                ]);
            }

            // prepare appointment data
            $appointmentData = [
                'lawyerId' => $validatedData['lawyerId'],
                'clientId' => $clientId,
                'startTime' => $availableSlot->startTime,
                'endTime' => $availableSlot->endTime,
                'caseId' => $validatedData['caseId'] ?? null,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'] ?? null,
                'type' => $validatedData['type'],
                'status' => 'scheduled',
                'location' => $validatedData['location'] ?? null,
                'notes' => $validatedData['notes'] ?? null
            ];

            $appointment = Appointments::create($appointmentData);

            $availableSlot->update([
                'status' => 'booked'
            ]);

            DB::commit();

            // $lawyers = User::lawyers()->get();
            // $clients = User::clients()->get();

            $lawyer = User::find($appointment->lawyerId);
            $client = User::find($appointment->clientId);


            Notification::send([$lawyer, $client], new newAppointment($appointment));

            return response()->json([
                'success' => true,
                'message' => 'Appointment scheduled successfully',
                'appointment' => $appointment
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Appointment creation failed' . $e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 422);

        }

    }

    // method to validate booking permission
    private function validateBookingPermissions($currentUser, $lawyerId, $clientId)
    {
        // clerk can book for any lawyer or client
        if ($currentUser->isClerk()) {
            return true;
        }

        // lawyer can book for themselves and client
        if ($currentUser->isLawyer()) {
            if ($lawyerId != $currentUser->id) {
                throw ValidationException::withMessages([
                    'lawyerId' => ['you cannot book onbehalf of another lawyer']
                ]);
            }
            return true;
        }

        // client can only book for themselves
        if ($currentUser->isClient()) {
            if ($clientId != $currentUser->id) {
                throw ValidationException::withMessages([
                    'clientId' => ['you cannot book an appointment for someone else']
                ]);
            }
            return true;
        }

        throw ValidationException::withMessages([
            'user' => ['You are not authorized to book appointments']
        ]);
    }

    public function create()
    {
        $lawyers = User::lawyers()->get();
        $clients = User::clients()->get();
        $cases = Cases::all();
        $availableSlot = Available_slots::where('status', 'available')->first();
        return view('appointments.add', compact(
            'lawyers',
            'clients',
            'cases',
            'availableSlot'
        ));
    }

    public function edit(Appointments $appointment, $id)
    {
        // authorize edit access
        $this->authorize('edit', $appointment);

        $appt = $appointment::with('lawyer', 'client', 'case')->findOrFail($id);
        $lawyers = User::lawyers()->get();
        $clients = User::clients()->get();
        $cases = Cases::all();
        // $getAvailableSlot = Available_slots::where('status', 'available')->first();
        return view('appointments.edit', [
            'appt' => $appt,
            'lawyers' => $lawyers,
            'clients' => $clients,
            'cases' => $cases,
            // 'getAvailableSlot' => $getAvailableSlot,
            'availableSlot' => $appointment->getAvailableSlotAttribute()


        ]);
    }

    public function update(Request $request, Appointments $appointment, $id)
    {
        $currentUser = auth()->user();

        // authorize update
        $this->authorize('edit', $appointment);

        $appt = Appointments::findOrFail($id);

        Db::beginTransaction();
        if ($request->isMethod('put')) {
            try {
                $validatedData = $request->validate([
                    'lawyerId' => 'sometimes|exists:users,id',
                    'clientId' => 'nullable|exists:users,id',
                    'availableSlotId' => 'sometimes|exists:available_slots,id',
                    'caseId' => 'nullable|exists:cases,id',
                    'title' => 'sometimes|string|max:255',
                    'description' => 'nullable|string',
                    'type' => 'sometimes|in:consultation,case_meeting,court_appearance',
                    'status' => 'in:scheduled,confirmed,completed,cancelled',
                    'location' => 'nullable|string',
                    'notes' => 'nullable|string'
                ]);

                $clientId = null;
                if ($currentUser->isClient()) {
                    $clientId = $currentUser->id;
                } elseif (isset($validatedData['clientId'])) {
                    $clientId = $validatedData['clientId'];
                } else {
                    $clientId = $currentUser->id;
                }

                $this->validateBookingPermissions(
                    $currentUser,
                    $validatedData['lawyerId'],
                    $clientId
                );


                // determine if time slot changed
                $newSlot = null;
                if (
                    isset($validatedData['availableSlotId'])
                    && $validatedData['availableSlotId'] != $appointment->availableSlotId
                ) {
                    $newSlot = Available_slots::findOrFail($validatedData['availableSlotId']);

                    // release old slot
                    $oldSlot = Available_slots::find($appointment->availableSlotId);
                    if ($oldSlot) {
                        $oldSlot->update(['status' => 'available']);
                    }
                    $conflictingAppointment = Appointments::where('lawyerId', $validatedData['lawyerId'])
                        ->where(function ($query) use ($newSlot) {
                            $query->whereBetween('startTime', [$newSlot->startTime, $newSlot->endTime])
                                ->orWhereBetween('endTime', [$newSlot->startTime, $newSlot->endTime])
                                ->orWhere(function ($q) use ($newSlot) {
                                    $q->where('startTime', '<=', $newSlot->startTime)
                                        ->where('endTime', '>=', $newSlot->endTime);
                                });
                        })
                        ->exists();

                    if ($conflictingAppointment) {
                        throw ValidationException::withMessages([
                            'startTime' => ['This time slot is already booked']
                        ]);
                    }

                    // $appointment->startTime = $newSlot->startTime;
                    // $appointment->endTime = $newSlot->endTime;
                    $newSlot->update(['status' => 'booked']);
                }

                // $clientId = $currentUser->isClient() ? $currentUser->id
                //     :($validatedData['clientId']);



                $appointmentData = [
                    'lawyerId' => isset($validatedData['lawyerId']) ? $validatedData['lawyerId'] : $appointment->lawyerId,
                    'clientId' => $clientId,
                    'startTime' => isset($newSlot) ? $newSlot->startTime : $appointment->startTime,
                    'endTime' => isset($newSlot) ? $newSlot->endTime : $appointment->endTime,
                    'caseId' => $validatedData['caseId'] ?? $appointment->caseId,
                    'title' => $validatedData['title'] ?? $appointment->title,
                    'description' => $validatedData['description'] ?? $appointment->description,
                    'type' => $validatedData['type'] ?? $appointment->type,
                    'status' => $validatedData['status'] ?? $appointment->status,
                    'location' => $validatedData['location'] ?? $appointment->location,
                    'notes' => $validatedData['notes'] ?? $appointment->notes
                ];

                $appt->update($appointmentData);

                Db::commit();

                $client = User::find($appointment->clientId);
                $lawyer = User::find($appointment->lawyerId);
                if ($lawyer && $client) {
                    // Send the notification
                    Notification::send([$lawyer, $client], new updatedAppt($appointment));
                } else {
                    Log::error('Lawyer or Client not found for appointment ID ' . $appointment->id);
                }

                // $lawyer = User::find($appointment->lawyerId);
                // $client = User::find($appointment->clientId);

                // Notification::send([$lawyer, $client], new updatedAppt($appointment));

                return response()->json([
                    'success' => true,
                    'message' => 'Appointment updated successfully',
                    'appointment' => $appointment
                ], 201);
                // return redirect()->route('appointments.view')->with('success', 'Appointment updated successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Appointment update failed: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'errors' => $e->getMessage()
                ], 422);

                // return redirect()->route('appointments.view')->with('message', 'Appointment update failed');
            }
        }
    }

    public function cancel(Appointments $appointment, $id)
    {
        // Authorize cancelation
        $this->authorize('cancel', $appointment);

        DB::beginTransaction();

        try {
            $cancelled = $appointment::findOrFail($id);
            $cancelled->update(['status' => 'cancelled']);

            // release the slot
            $availableSlot = Available_slots::where('startTime', $appointment->startTime)
                ->where('endTime', $appointment->endTime)
                ->first();

            if ($availableSlot) {
                $availableSlot->update(['status' => 'available']);
            } else {
                Log::warning("No available slot found for appointment ID: {$appointment->id}");
            }

            DB::commit();

            $lawyer = User::find($appointment->lawyerId);
            $client = User::find($appointment->clientId);

            if ($lawyer && $client) {
                // Send the notification
                Notification::send([$lawyer, $client], new updatedAppt($appointment));
            } else {
                Log::error('Lawyer or Client not found for appointment ID ' . $appointment->id);
            }

            // $lawyer = User::find($appointment->lawyerId);
            // $client = User::find($appointment->clientId);

            // Notification::send([$lawyer, $client], new cancelledAppt($appointment));

            return redirect()->route('appointments.view')->with('success', 'Appointment canceled');
        } catch (\exception $e) {
            DB::rollBack();
            Log::error('Appointment cancellation failed: ' . $e->getMessage());

            return redirect()->route('appointments.view')->with('message', 'Failed to cancel appointment');
        }
    }

    public function getAvailableSlots($lawyerId, $date)
    {
        try {
            $parsedDate = Carbon::parse($date);

            if ($parsedDate->isPast() && !$parsedDate->isToday()) {
                return response()->json([
                    'error' => 'invalid date',
                    'message' => 'date cannot be in the past'
                ], 422);
            }

            $slots = Available_slots::where('lawyerId', $lawyerId)
                ->whereDate('startTime', $parsedDate->toDateString())
                ->where('status', 'available')
                ->where('startTime', '>=', now())
                ->whereRaw('DAYOFWEEK(startTime) NOT IN (1, 7)')
                ->get(['id', 'startTime', 'endTime', 'status']);

            return response()->json($slots);
        } catch (\Exception $e) {
            Log::error('Error fetching available slots: ' . $e->getMessage());

            return response()->json([
                'error' => 'Unable to fetch available slots',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
