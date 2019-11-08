<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Industries;

class IndustriesController extends Controller
{
    public function getAll(Request $request)
    {
        $list = Industries::all();
        return response()->json($list);
    }
}
