<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\properties;
use App\Models\User;

use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $propertyList = properties::where('address', 'like', '%' . $searchTerm . '%')
            ->paginate($perPage);

        return view('properties.index', compact('propertyList'));
    }

    public function showAddProperty()
    {
        $clients = User::clients()->get();
        return view('properties.add', compact('clients'));
    }

    public function showEditProperty($id)
    {
        $prop = properties::find($id);
        $clients = User::clients()->get();
        return view('properties.edit', [
            'prop' => $prop,
            'clients' => $clients
        ]);
    }
    public function updateProp(Request $request, $id)
    {
        // find the property to be updated
        $prop = properties::findOrFail($id);

        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'clientId' => 'required|exists:users,id',
                'address' => 'required|string|max:100',
                'rate' => 'required|integer|min:1000',
                'percentage' => 'required|integer|min:0|max:50',
            ]);

            // update therequired field
            $prop->update($validatedData);
            // Redirect to the view properties page
            return redirect()->route('properties.view')
                ->with('message', 'Property updated successfully');
        }
    }

    public function addProp(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'clientId' => 'required|exists:users,id',
                'address' => 'required|string|max:100',
                'rate' => 'required|integer|min:1000',
                'percentage' => 'required|integer|min:0|max:50',
            ]);

            properties::create($validatedData);

            return redirect()->back()->with('message', 'Property added successfully');
        }
    }
}








