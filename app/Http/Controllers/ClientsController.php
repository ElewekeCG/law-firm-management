<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if($user->role === 'lawyer' || $user->role === 'clerk') {
            $perPage = $request->input('dataTable_length', 10);
            // Default to an empty string if no search term
            $searchTerm = $request->input('search', '');

            // fetch clients with optional filtering and pagination
            $clients = User::clients()
            ->where('name', 'like', '%' . $searchTerm . '%')
            ->paginate($perPage);

            return view('clients.index', compact('clients' ));
        } else {
            abort(403, 'unauthorized');
        }
    }
}
