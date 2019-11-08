<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CompanySize;
use Illuminate\Support\Facades\DB;


class CompanySizesController extends Controller
{
    public function getAll(Request $request)
    {
        $list = CompanySize::select('id', DB::raw("CONCAT(size_from,'-',size_to) AS size"))->get();
        return response()->json($list);
    }
}
