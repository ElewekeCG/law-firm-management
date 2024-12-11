<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\ClientModel;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function showClientsList(Request $request)
    {
        // Default to 10 if not specified
        $perPage = $request->input('dataTable_length', 10);
        // Default to an empty string if no search term
        $searchTerm = $request->input('search', '');

        // filter by search term if provided
        $clientList = ClientModel::where('firstName', 'like', '%' . $searchTerm . '%')
        ->paginate($perPage);

        return view('clients.clientList', compact( 'clientList'));
    }

    public function showAddClient()
    {
        return view('clients.addClient');
    }

    public function showEditClient($id)
    {
        $client = ClientModel::find($id);
        return view('clients.editClient', compact('client'));
    }

    public function updateClient(Request $request, $id)
    {
        // Find the client to be updated
        $client = ClientModel::findOrFail($id);

        if ($request->isMethod('put')) {

            // Validate the request data
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'address' => 'required|string|max:100',
                'phoneNumber' => 'required|string|max:15',
                'email' => 'required|email|max:50' ,
                'propertyManaged' => 'required|boolean',
            ]);

            // Update the client's data using the validated data
            $client->update([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'address' => $validatedData['address'],
                'phoneNumber' => $validatedData['phoneNumber'],
                'email' => $validatedData['email'],
                'propertyManaged' => $validatedData['propertyManaged'],
            ]);

            // Redirect to the view clients page
            return redirect()->route('clients.clientList')
                ->with('message', 'Client updated successfully');
        }
    }


    public function addClient(Request $request)
    {
        if ($request->isMethod('post')) {

            // Validate the request data
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'address' => 'required|string|max:100',
                'phoneNumber' => 'required|string|max:15|unique:clients,phoneNumber',
                'email' => 'required|email|max:50|unique:clients,email' ,
                // 'propertyManaged' => 'boolean',
            ]);

            // Create a new client using the validated data
            $client = ClientModel::create([
                'firstName' => $validatedData['firstName'],
                'lastName' => $validatedData['lastName'],
                'address' => $validatedData['address'],
                'phoneNumber' => $validatedData['phoneNumber'],
                'email' => $validatedData['email'],
                // 'propertyManaged' => $validatedData['propertyManaged'],
            ]);

            return redirect()->back()->with('message', 'Client added Succsessfully');
        }
    }

    
}
