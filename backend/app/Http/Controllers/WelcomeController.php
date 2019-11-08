<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        return view('welcome');
    }
    public function page403(Request $request){
        return view('errors.403');
    }
}
