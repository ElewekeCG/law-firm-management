<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showLandingPage()
    {
        return view('landingPage.welcome');
    }
}
