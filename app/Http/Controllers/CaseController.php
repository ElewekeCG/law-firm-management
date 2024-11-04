<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\cases;
use App\Models\clients;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $caseList = cases::where('title', 'like', '%' . $searchTerm . '%')
            ->paginate($perPage);

        return view('cases.allCases', compact('caseList'));
    }

    public function showAddCase()
    {
        $clients = clients::all();
        return view('cases.addCase', compact('clients'));
    }

    public function showEditCase($id)
    {
        $case = cases::find($id);
        $clients = clients::all();
        return view('cases.editCase', [
            'case' => $case,
            'clients' => $clients
        ]);
    }

    public function updateCase(Request $request, $id)
    {
        // find the case to be updated
        $case = cases::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'title' => 'required|string|max:100',
                'type' => 'required|string|max:20',
                'status' => 'required|string|max:20',
                'clientId' => 'required|exists:clients,id',
                'suitNumber' => 'required|string|max:20',
                'startDate' => 'required|date',
                'nextAdjournedDate' => 'required|date:d/m/Y g:i A | after:now',
                'assignedCourt' => 'required|string|max:50',
            ]);

            // update therequired field
            $case->update([
                'title' => $validatedData['title'],
                'type' => $validatedData['type'],
                'status' => $validatedData['status'],
                'clientId' => $validatedData['clientId'],
                'suitNumber' => $validatedData['suitNumber'],
                'startDate' => $validatedData['startDate'],
                'nextAdjournedDate' => $validatedData['nextAdjournedDate'],
                'assignedCourt' => $validatedData['assignedCourt'],
            ]);

            // Redirect to the view cases page
            return redirect()->route('cases.allCases')
                ->with('message', 'Case updated successfully');
        }
    }

    public function addCase(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'title' => 'required|string|max:100',
                'type' => 'required|string|max:20',
                'status' => 'required|string|max:20',
                'clientId' => 'required|exists:clients,id',
                'suitNumber' => 'required|string|max:20|unique:cases,suitNumber',
                'startDate' => 'required|date',
                'nextAdjournedDate' => 'required|date:d/m/Y g:i A | after:now',
                'assignedCourt' => 'required|string|max:50',
            ]);

            // $nextAdjournedDate = Carbon::createFromFormat('m/d/Y g:i A', $validatedData['nextAdjournedDate']);
            // if ($nextAdjournedDate === false) {
            //     return redirect()->back()->withErrors(['nextAdjournedDate' => 'Invalid date/time format.']);
            // }

            $case = cases::create([
                'title' => $validatedData['title'],
                'type' => $validatedData['type'],
                'status' => $validatedData['status'],
                'clientId' => $validatedData['clientId'],
                'suitNumber' => $validatedData['suitNumber'],
                'startDate' => $validatedData['startDate'],
                'nextAdjournedDate' => $validatedData['nextAdjournedDate'],
                // 'nextAdjournedDate' => Carbon::createFromFormat('m/d/Y g:i A', $validatedData['nextAdjournedDate'])->format('d/m/Y h:i A'),
                'assignedCourt' => $validatedData['assignedCourt'],
            ]);

            return redirect()->back()->with('message', 'Case added successfully');
        }
    }

    // public function getClients(Request $request)
    // {
    //     $search = $request->input('search');

    //     $clientList = clients::select('id', 'firstName', 'lastName')
    //         ->when($search, function ($query) use ($search) {
    //             return $query->where(function ($q) use ($search) {
    //                 $q->where('firstName', 'LIKE', "%{$search}%")
    //                     ->orWhere('lastName', 'LIKE', "%{$search}%");
    //             });
    //         })
    //         ->get()
    //         ->map(function ($clientList) {
    //             return [
    //                 'id' => $clientList->id,
    //                 'full_name' => $clientList->full_name
    //             ];
    //         });

    //     return response()->json($clientList);
    // }


}
