<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\User;
use App\DocumentDetail;
use App\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContractsController extends Controller
{
    public function index(Request $request)
    {
        $list = DocumentDetail::with(['documents.recipients','create.client'])->get();
        foreach ($list as $key => $document_detail) {
            $document_detail['amount'] = sizeof($document_detail['documents']);
            if(isset($document_detail['documents'][0]['recipients']))
                $document_detail['recipients'] = $document_detail['documents'][0]['recipients'];
            else
                $document_detail['recipients'] = [];
            unset($list[$key]['documents']);
        }

        // return ($list);
        return view('admin.contract.all', ['status'=>null, 'msg'=>null, 'list'=>$list]);
    }

    public function destroy($document_id)
    {
        $document_detail = DocumentDetail::where("document_id",$document_id)->first();
        $document_detail->delete();
        $list = Document::where('document_id',$document_id)->get();
        foreach ($list as $document) {
            Storage::disk('public')->delete($document['document_file']);
            $document->delete();
        }
        return redirect()->back()->with(['status'=>'success','msg'=>'Delete successfully !']);
    }
}
