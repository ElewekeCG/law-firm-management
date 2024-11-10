<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\clients;
use App\Models\appointmentsModel;


class Appointments extends Controller
{
    public function index(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // Get start and end dates from the request (if provided)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = appointmentsModel::query();

        // filter by search term if provided
        if (!empty($searchTerm)) {
            $query->where('clientId', 'like', '%' . $searchTerm . '%');
        }

        // filter by date range if both dates are provided
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('appointmentDate', [$startDate, $endDate]);
        } elseif (!empty($startDate)) {
            // filter by start date only
            $query->where('appointmentDate', '>=', $startDate);
        } elseif (!empty($endDate)) {
            // filter by end date only
            $query->where('appointmentDate', '<=', $endDate);
        }

        // paginate the result
        $appList = $query->paginate($perPage);

        return view('appointments.all', compact('appList'));
    }

    public function showAdd()
    {
        $clients = clients::all();
        return view('appointments.addCase', compact('clients'));
    }

    public function showEdit($id)
    {
        $appt = appointmentsModel::find($id);
        $clients = clients::all();
        return view('appointments.edit', [
            'appt' => $appt,
            'clients' => $clients
        ]);
    }

    public function updateAppt(Request $request, $id)
    {
        // find the appointment to be updated
        $appt = appointmentsModel::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'clientId' => 'required|exists:clients,id',
                'appointmentDate' => 'required|date',
                'fees' => 'required|integer|min:0',
                'amountPaid' => 'required|integer|min:0',
                'balance' => 'required|integer|min:0',
                'instructions' => 'string',
            ]);

            // update the required field
            $appt->update([
                'clientId' => $validatedData['clientId'],
                'appointmentDate' => $validatedData['appointmentDate'],
                'fees' => $validatedData['fees'],
                'amountPaid' => $validatedData['amountPaid'],
                'balance' => $validatedData['balance'],
                'instructions' => $validatedData['instructions'],
            ]);

            // Redirect to the view appointments page
            return redirect()->route('appointments.all')
                ->with('message', 'Appointment updated successfully');
        }
    }

    public function addAppt(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'clientId' => 'required|exists:clients,id',
                'appointmentDate' => 'required|date',
                'fees' => 'required|integer|min:0',
                'amountPaid' => 'required|integer|min:0',
                'balance' => 'required|integer|min:0',
                'instructions' => 'string',
            ]);

            $appt = appointmentsModel::create([
                'clientId' => $validatedData['clientId'],
                'appointmentDate' => $validatedData['appointmentDate'],
                'fees' => $validatedData['fees'],
                'amountPaid' => $validatedData['amountPaid'],
                'balance' => $validatedData['balance'],
                'instructions' => $validatedData['instructions'],
            ]);

            return redirect()->back()->with('message', 'Appointment added successfully');
        }
    }
}
