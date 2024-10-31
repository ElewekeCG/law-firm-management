<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Proceedings extends Controller
{
    public function showAdd()
    {
        return view('cases.proceedings');
    }

    public function showEdit()
    {
        return view('cases.editPro');
    }
}
