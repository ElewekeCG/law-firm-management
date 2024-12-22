<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\cases;
use App\Models\proceedings;

use Illuminate\Http\Request;

class ProceedingsController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    // Start with a base query that includes the case relationship
    $records = proceedings::with('case');

    if ($user->role === 'lawyer' || $user->role === 'clerk') {
        // Lawyers and clerks can view all records
        $records;
    } elseif ($user->role === 'client') {
        // Clients can only view records for their cases
        $records->whereHas('case', function($query) use ($user) {
            $query->where('clientId', $user->id);
        });
    } else {
        abort(403, 'Unauthorized');
    }

    // Search and pagination
    $perPage = $request->input('dataTable_length', 10);
    $searchTerm = $request->input('search', '');

    $recordsList = $records->when($searchTerm, function($query) use ($searchTerm) {
            return $query->where(function($q) use ($searchTerm) {
                $q->WhereHas('case', function($caseQuery) use ($searchTerm) {
                      $caseQuery->where('title', 'like', '%' . $searchTerm . '%');
                  });
            });
        })
        ->paginate($perPage);

    return view('cases.records', compact('recordsList'));
}

    public function showAdd()
    {
        $cases = cases::all();
        return view('cases.proceedings', compact('cases'));
    }

    public function showEdit($id)
    {
        $pro = proceedings::find($id);
        $cases = cases::all();
        return view('cases.editPro', [
            'pro' => $pro,
            'cases' => $cases
        ]);
    }

    public function updatePro(Request $request, $id)
    {
        // find the case to be updated
        $pro = proceedings::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'caseId' => 'required|exists:cases,id',
                'description' => 'required|string',
                'requiredDoc' => 'nullable|string',
                'dueDate' => 'nullable|date',
                'docStatus' => 'required|in:pending,done'
            ]);

            // update the required field
            $pro->update($validatedData);

            // Redirect to the view cases page
            return redirect()->route('cases.viewRecord')
                ->with('message', 'Record updated successfully');
        }
    }

    public function addPro(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'caseId' => 'required',
                'description' => 'required|string',
                'requiredDoc' => 'nullable|string',
                'dueDate' => 'nullable|date',
                'docStatus' => 'pending'
            ]);

            proceedings::create($validatedData);

            return redirect()->back()->with('message', 'Record added successfully');
        }
    }
}
