<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function listLawyers()
    {
        $lawyers = User::lawyers()->get();
        return view('lawyers.index', compact('lawyers'));
    }
}








