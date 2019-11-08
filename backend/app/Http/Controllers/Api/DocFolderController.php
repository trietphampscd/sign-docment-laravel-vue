<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocFolder;

class DocFolderController extends Controller
{
    public function getAll(Request $request){
        $list = DocFolder::where("user_id",$request->user()->id)->get();
        return response()->json([
            'status' => true,
            'list' => $list,
        ],200);
    }

    public function updateName(Request $request){
        $id = $request->id;
        $obj = DocFolder::find($id);
        $data = $request->all();
        foreach ($data as $key => $value) {
            $obj[$key] = $value;
        }
        $obj->save();
        return response()->json([
            'status' => true,
            'data' => $obj
        ],200);
    }

    public function addDocFolder(Request $request){
        $obj = new DocFolder();
        $data = $request->all();
        foreach ($data as $key => $value) {
            $key === 'parent_id' && $value == 0 ? $value = null : $value;
            $obj[$key] = $value;
        } 
        $obj->user_id = $request->user()->id; 
        $obj->save(); 
        return response()->json([
            'status' => true,
            'data' => $obj
        ],200);    
    }

    public function delete(Request $request){
        $docfol = DocFolder::find($request->id);
        // $children = $docfol->children()->delete();
        $docfol->delete();
        return response()->json([
            'status' => true,
        ],200);    
    }

}
