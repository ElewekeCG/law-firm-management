<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\User;
use App\Notifications\CaseUpdated;
use Illuminate\Http\Request;
use Notification;

class CaseController extends Controller
{
    public function index(Request $request, Cases $cases)
    {
        $user = auth()->user();

        if($user->role === 'lawyer' || $user->role === 'clerk') {
            // lawyers and clerks can view all cases
            $cases = Cases::query();
        } elseif ($user->role === 'client') {
            // clients can only view cases they are involved in
            $cases = Cases::where('clientId', $user->id);
        } else {
            abort(403, 'Unauthorized');
        }

        /**
            * search and pagination
            * Default to 10 if not specified
        **/
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $caseList = $cases->where('title', 'like', '%' . $searchTerm . '%')
            ->paginate($perPage);

        return view('cases.allCases', compact('caseList'));
    }

    public function showAddCase(Cases $cases)
    {
        $this->authorize('create', $cases);
        $lawyers = User::lawyers()->get();
        $clients = User::clients()->get();
        return view('cases.addCase', compact('lawyers', 'clients'));
    }

    public function showEditCase($id, Cases $cases)
    {
        $this->authorize('update', $cases);
        $case = Cases::find($id);
        $lawyers = User::lawyers()->get();
        $clients = User::clients()->get();
        return view('cases.editCase', compact('case', 'lawyers', 'clients'));
    }

    public function updateCase(Request $request, $id, Cases $cases)
    {
        $this->authorize('update', $cases);
        // find the case to be updated
        $case = Cases::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'suitNumber' => 'sometimes|string|max:20',
                'clientId' => 'sometimes|exists:users,id',
                'lawyerId' => 'sometimes|exists:users,id',
                'title' => 'sometimes|string|max:100',
                'type' => 'sometimes|string|max:20',
                'status' => 'sometimes|string|max:255',
                'startDate' => 'sometimes|date',
                'assignedCourt' => 'sometimes|string|max:255',
            ]);

            // update the required field
            $case->update($validatedData);

            // notify client associated with case
            $client = User::find($case->clientId);
            if ($client) {
                $client->notify(new CaseUpdated($case));
            }

            // notify lawyers
            $lawyers = User::lawyers()->get();
            Notification::send($lawyers, new CaseUpdated($case));

            // Redirect to the view cases page
            return redirect()->route('cases.allCases')
                ->with('message', 'Case updated successfully');
        }
    }

    public function addCase(Request $request, Cases $cases)
    {
        // authorize case creation
        $this->authorize('create', $cases);
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'suitNumber' => 'required|string|max:20|unique:cases,suitNumber',
                'clientId' => 'required|exists:users,id',
                'lawyerId' => 'required|exists:users,id',
                'title' => 'required|string|max:100',
                'type' => 'required|string|max:20',
                'status' => 'required|string|max:255',
                'startDate' => 'required|date',
                'assignedCourt' => 'required|string|max:255',
            ]);

            Cases::create($validatedData);

            return redirect()->back()->with('message', 'Case added successfully');
        }
    }
}
