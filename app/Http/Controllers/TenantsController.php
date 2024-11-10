<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\tenants;
use App\Models\properties;
use Illuminate\Validation\Rule;


class TenantsController extends Controller
{
    public function index(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $tenants = tenants::where('firstName', 'like', '%' . $searchTerm . '%')
            ->orWhere('lastName', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('property', function($query) use ($searchTerm) {
                $query->where('address', 'like', '%' . $searchTerm . '%');
            })
            ->paginate($perPage);


        return view('tenants.index', compact('tenants'));
    }

    public function showAdd()
    {
        $props = properties::all();
        return view('tenants.addTenant', compact('props'));
    }

    public function showEdit($id)
    {
        $tenant = tenants::find($id);
        $props = properties::all();
        return view('tenants.edit', [
            'tenant' => $tenant,
            'props' => $props
        ]);
    }

    public function update (Request $request, $id)
    {
        // find the case to be updated
        $tenant = tenants::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'email' => 'required|string|max:50',
                'paymentType' => ['required', Rule::in(['yearly', 'monthly'])],
                'accomType' => 'required|string|max:20',
                'rentAmt' => 'required|integer|min:1000',
                'propertyId' => 'required|exists:properties,id',
            ]);

            // update the required field
            $tenant->update([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'email' =>$validatedData['email'],
                'paymentType' =>$validatedData['paymentType'],
                'accomType' =>$validatedData['accomType'],
                'rentAmt' =>$validatedData['rentAmt'],
                'propertyId' =>$validatedData['propertyId'],
            ]);

            // Redirect to the view cases page
            return redirect()->route('tenants.view')
                ->with('message', 'Tenant updated successfully');
        }
    }

    public function addTenant(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'email' => 'required|string|max:50|unique:properties,id',
                'paymentType' => ['required', Rule::in(['yearly', 'monthly'])],
                'accomType' => 'required|string|max:20',
                'rentAmt' => 'required|integer|min:1000',
                'propertyId' => 'required|exists:properties,id',
            ]);


            $tenant = tenants::create([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'email' =>$validatedData['email'],
                'paymentType' =>$validatedData['paymentType'],
                'accomType' =>$validatedData['accomType'],
                'rentAmt' =>$validatedData['rentAmt'],
                'propertyId' =>$validatedData['propertyId'],
            ]);

            return redirect()->back()->with('message', 'Tenant added successfully');
        }
    }
}
