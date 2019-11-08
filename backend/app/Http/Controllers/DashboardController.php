<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\DocumentDetail;

class DashboardController extends Controller
{
    public function showData()
    {
        $totaluser = User::where('admin',0)->count();
        $totalContracts = DocumentDetail::count();
        return view('admin.dashboard', ['totaluser' => $totaluser, 'totalContracts' => $totalContracts]);
    }
}
