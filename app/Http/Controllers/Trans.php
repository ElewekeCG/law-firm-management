<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\properties;
use App\Models\User;
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
        $transList = transactions::query()
        ->with(['tenant', 'client', 'property']) // Eager load relationships
        ->when($searchTerm, function ($query, $searchTerm) {
            $query->whereHas('tenant', function ($q) use ($searchTerm) {
                    $q->where('firstName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('lastName', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('client', function ($q) use ($searchTerm) {
                    $q->where('firstName', 'like', '%' . $searchTerm . '%')
                    ->orWhere('lastName', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('property', function ($q) use ($searchTerm) {
                    $q->where('address', 'like', '%' . $searchTerm . '%');
                })
                ->orWhere('paymentDate', $searchTerm);
        })
        ->paginate($perPage);

        return view('transactions.index', compact('transList'));
    }

    public function showAdd()
    {
        $properties = properties::all();
        $clients = User::clients()->get();
        $tenants = tenants::all();
        return view('transactions.add', compact('properties', 'clients', 'tenants'));
    }

    public function showEdit($id)
    {
        $trans = transactions::find($id);
        $properties = properties::all();
        $clients = User::clients()->get();
        $tenants = tenants::all();
        return view('transactions.edit', [
            'properties' => $properties,
            'clients' => $clients,
            'tenants' => $tenants,
            'trans' => $trans,
        ]);
    }

    public function updateTrans(Request $request, $id)
    {
        // find the transaction to be updated
        $trans = transactions::findOrFail($id);

        if ($request->isMethod('put')) {
            $rules = [
                'amount' => 'required|integer|min:1',
                'paymentDate' => 'required|date',
                'type' => 'required|in:credit,debit',
                'subType' => 'nullable|required_if:type,credit|in:legalFee,rent',
                'narration' => 'required|string|max:1024',
            ];

            if($request->input('subType') === 'rent') {
                $rules['tenantId'] = 'required|exists:tenants,id';
                $rules['propertyId'] = 'required|exists:properties,id';
            } elseif ($request->input('subType') === 'legalFee') {
                $rules['clientId'] = 'required|exists:users,id';
            } elseif ($request->input('type' === 'debit')) {
                $rules['propertyId'] = 'required|exists:properties,id';
            }

            $validatedData = $request->validate($rules);

            // remove null values before creating record
            $data = array_filter($validatedData, function($value) {
                return !is_null($value);
            });

            $trans->update($data);

            // Redirect to the view transactions page
            return redirect()->route('transactions.view')
                ->with('message', 'Transaction updated successfully');
        }
    }

    public function addTrans(Request $request)
    {
        if($request->isMethod('post')) {
            $rules = [
                'amount' => 'required|integer|min:1',
                'paymentDate' => 'required|date',
                'type' => 'required|in:credit,debit',
                'subType' => 'nullable|required_if:type,credit|in:legalFee,rent',
                'narration' => 'required|string|max:1024',
            ];

            if($request->input('subType') === 'rent') {
                $rules['tenantId'] = 'required|exists:tenants,id';
                $rules['propertyId'] = 'required|exists:properties,id';
            } elseif ($request->input('subType') === 'legalFee') {
                $rules['clientId'] = 'required|exists:users,id';
            } elseif ($request->input('type' === 'debit')) {
                $rules['propertyId'] = 'required|exists:properties,id';
            }

            $validatedData = $request->validate($rules);

            // remove null values before creating record
            $data = array_filter($validatedData, function($value) {
                return !is_null($value);
            });

            transactions::create($data);

            return redirect()->back()->with('message', 'Transaction added successfully');
        }
    }
}









