<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\properties;
use App\Models\clients;
use App\Models\tenants;
use App\Models\transactions;

class Trans extends Controller
{
    public function index(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $transList = transactions::where('propertyId', 'like', '%' . $searchTerm . '%')
            ->paginate($perPage);

        return view('transactions.index', compact('transList'));
    }

    public function showAdd()
    {
        $properties = properties::all();
        $clients = clients::all();
        $tenants = tenants::all();
        return view('transactions.add', compact('properties', 'clients', 'tenants'));
    }

    public function showEdit($id)
    {
        $trans = transactions::find($id);
        $properties = properties::all();
        $clients = clients::all();
        $tenants = tenants::all();
        return view('transactions.edit', [
            'properties' => $properties,
            'clients' => $clients,
            'tenants'=> $tenants
        ]);
    }

    public function updateTrans(Request $request, $id)
    {
        // find the transaction to be updated
        $trans = transactions::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'amount' => 'required|integer|min:0',
                'paymentDate' => 'required|date',
                'entityType' => 'required|in:App\Models\tenants,App\Models\clients',
                'entityId' => 'required|integer|exists:'.$request->input('entity_type').',id',
                'type' => 'required|in:credit,debit',
                'subType' => 'nullable|string|required_if:type,credit|in:legalFee,rent',
                'propertyId' => [
                    'nullable',
                    'required_if:type,debit',
                    'required_if:subtype,rent',
                    'exists:properties,id'
                ],
                'narration' => 'required|exists:clients,id',
            ]);

            // update the required field
            $trans->update([
                'amount' => $validatedData['amount'],
                'paymentDate' => $validatedData['paymentDate'],
                'entityType' => $validatedData['entityType'],
                'entityId' => $validatedData['entityId'],
                'type' => $validatedData['type'],
                'subType' => $validatedData['subType'],
                'propertyId' => $validatedData['propertyId'],
                'narration' => $validatedData['narration'],
            ]);

            // Redirect to the view transactions page
            return redirect()->route('transactions.index')
                ->with('message', 'Transaction updated successfully');
        }
    }

    public function addTrans(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'amount' => 'required|integer|min:0',
                'paymentDate' => 'required|date',
                'entityType' => 'required|in:App\Models\tenants,App\Models\clients',
                'entityId' => 'required|integer|exists:'.$request->input('entity_type').',id',
                'type' => 'required|in:credit,debit',
                'subType' => 'nullable|string|required_if:type,credit|in:legalFee,rent',
                'propertyId' => [
                    'nullable',
                    'required_if:type,debit',
                    'required_if:subtype,rent',
                    'exists:properties,id'
                ],
                'narration' => 'required|exists:clients,id',
            ]);

            $newProp = properties::create([
                'amount' => $validatedData['amount'],
                'paymentDate' => $validatedData['paymentDate'],
                'entityType' => $validatedData['entityType'],
                'entityId' => $validatedData['entityId'],
                'type' => $validatedData['type'],
                'subType' => $validatedData['subType'],
                'propertyId' => $validatedData['propertyId'],
                'narration' => $validatedData['narration'],
            ]);

            return redirect()->back()->with('message', 'Transaction added successfully');
        }
    }
}









