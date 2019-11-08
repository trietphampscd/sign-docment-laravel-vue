<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentsController extends Controller
{
    public function getAll(Request $request)
    {
        $list = Department::all();
        return response()->json($list);
    }
}
