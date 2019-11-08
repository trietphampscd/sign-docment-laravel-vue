<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Input;
use App\Http\Controllers\Api\PDFsController;
use App\Document;
use App\DocumentAction;
use App\SignRequest;
use App\Client;
use App\DocumentDetail;
use setasign\Fpdi;
use DB;
use Carbon\Carbon;

class DocumentsController extends Controller
{
    private $document_ext = ['pdf'];

    public function getDocuments(Request $request)
    {
        $documents = Document::get();
        $images = [];
        $objPDF = new PDFsController();
        foreach ($documents as $document) {
            if(isset($document->document_file)){
                $images = $objPDF->createObjImage($document->document_file,$document->document_id);
                $document['images'] = $images;
            } else {
                $document['images'] = [];
            }

            $doc_url = Storage::url($document->document_file);
            $document->document_file = asset($doc_url);
        }
        return response()->json([
            'documents' => $documents
        ], 200);
    }

    public function getDocument(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document == null) {
            return response(null, 404);
        }
        $images = [];
        $objPDF = new PDFsController();
        $images = $objPDF->createObjImage($document->document_file, $document->document_id);
        $document['images'] = $images;
        $doc_url = Storage::url($document->document_file);
        $document->document_file = asset($doc_url);
        return response()->json([
            'document' => $document
        ], 200);
    }

    public function getDocumentInfo(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document == null) {
            return response(null, 404);
        }
       
        return response()->json([
            'document' => $document
        ], 200);
    }

    public function uploadDocument(Request $request)
    {
        $objPDF = new PDFsController();
        $files = $request->file('filesBrowser');
        $uuid = $request->document_id;
        $list = array();
        // return $request->filesSocical;
        if($request->hasfile('filesBrowser') || $request->filesSocical){
            if($request->hasfile('filesBrowser')){
                foreach($request->file('filesBrowser') as $file)
                {
                    $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // $name = str_replace(" ","_",$name);
                    $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                    $file_name = $name.''.time();
                    $file_name = $this->normalizeString($file_name);
                    $draft_filename =  $file_name. '.'. $ext;
                    Storage::putFileAs('public/uploads/files', $file, $draft_filename);
                    $client = Client::where("user_id", $request->user()->id)->first();
                    $document = new Document([
                        'document_id' => $uuid,
                        'document_name' => $name. '.'. $ext,
                        'document_file' => 'uploads/files/'. $draft_filename,
                        'creator_id' => $client->id,
                        'doc_folder_id' => $request->doc_folder_id,
                        'action' => 'sign',
                        'status' => 'draft',
                    ]);
                    // echo "Start <br/>"; echo '<pre>'; print_r($document);echo '</pre>';exit("End Data");
                    if($ext != "pdf")
                        $document->document_file = $objPDF->convertFileToPDFAPI($document->document_file, $file_name);
                    $document->save();
                    $doc_url = Storage::url($document->document_file);
                    $document->document_file = asset($doc_url);
                    array_push($list, $document);
                }
            }

            if($request->filesSocical) {
                $filesSocial = json_decode($request->filesSocical,true);
                foreach($filesSocial  as $file)
                {
                    $client = Client::where("user_id", $request->user()->id)->first();
                    // $this->convertUrlDownSocial($file);
                    $draft_filename = '';
                    $downloadUrl = '';
                    $nameFile = '';
                    $ext = '';

                    if($file && isset($file['url'])) {
                        if ((strpos($file['url'], 'https://docs.google.com/spreadsheets/') !== false || strpos($file['url'], 'https://docs.google.com/presentation/') !== false || strpos($file['url'], 'https://docs.google.com/document/') !== false)) {
                            // $file_name = $file['name'].''.time();
                            $file_name =  $this->normalizeString($file['name']).time();
                            $draft_filename = $file_name. '.pdf';
                            if(strpos($file['url'], 'https://docs.google.com/spreadsheets/') !== false){
                                $nameFile = $file['name'].'.xlsx';
                                $downloadUrl = "https://docs.google.com/spreadsheets/d/".$file['id']."/export?format=pdf";
                            }else if(strpos($file['url'], 'https://docs.google.com/presentation/') !== false){
                                $nameFile = $file['name'].'.pptx';
                                $downloadUrl = "https://docs.google.com/presentation/d/".$file['id']."/export?format=pdf";
                            }else {
                                $nameFile = $file['name'].'.docx';
                                $downloadUrl = "https://docs.google.com/document/d/".$file['id']."/export?format=pdf";
                            }
                        } else {
                            $arrNameFile = explode('.', $file['name']);
                            $ext = end($arrNameFile);
                            $name = str_replace(".".$ext,"",$file['name']);
                            $file_name = $name.''.time();
                            $file_name =  $this->normalizeString($file_name);
                            $draft_filename = $file_name. '.'. $ext;
                            $downloadUrl = $file['downloadUrl'];
                            $nameFile = $file['name'];
                        }

                    } else {
                        $arrNameFile = explode('.', $file['name']);
                        $ext = end($arrNameFile);
                        $name = str_replace(".".$ext,"",$file['name']);
                        $file_name = $name.''.time();
                        $file_name =  $this->normalizeString($file_name);
                        $draft_filename = $file_name. '.'. $ext;
                        $downloadUrl = $file['downloadUrl'];
                        $nameFile = $file['name'];
                    }
                    $this->saveFileFromUrl($downloadUrl, $draft_filename);
                    $document = new Document([
                        'document_id' => $uuid,
                        'document_name' => $nameFile,
                        'document_file' => 'uploads/files/'. $draft_filename,
                        'creator_id' => $client->id,
                        'doc_folder_id' => $request->doc_folder_id,
                        'action' => 'sign',
                        'status' => 'draft',
                    ]);
                    if(!empty($ext) && $ext != "pdf")
                        $document->document_file = $objPDF->convertFileToPDFAPI($document->document_file, $file_name);
                    $document->save();
                    $doc_url = Storage::url($document->document_file);
                    $document->document_file = asset($doc_url);
                    array_push($list, $document);
                }
            }
            return response()->json([
                'list' => $list,
                'status' => true,
                'message' => 'Document has been uploaded.'
            ], 201);
        }
        return response()->json([
            'status'  => false
        ],404);
    }

    public function convertUrlDownSocial($file)
    {
        $draft_filename = '';
        $downloadUrl = '';

        if (strpos($file['url'], 'https://docs.google.com/spreadsheets/') !== false || strpos($file['url'], 'https://docs.google.com/presentation/') !== false || strpos($file['url'], 'https://docs.google.com/document/') !== false) {
            $file_name = $file['name'].''.time();
            $file_name =  $this->normalizeString($file_name);
            $draft_filename = $file_name. '.pdf';
            if(strpos($file['url'], 'https://docs.google.com/spreadsheets/') !== false){
                $downloadUrl = "https://docs.google.com/spreadsheets/d/".$file['id']."/export?format=pdf";
            }else if(strpos($file['url'], 'https://docs.google.com/presentation/') !== false){
                $downloadUrl = "https://docs.google.com/presentation/d/".$file['id']."/export?format=pdf";
            }else {
                $downloadUrl = "https://docs.google.com/document/d/".$file['id']."/export?format=pdf";
            }
        } else {
            $arrNameFile = explode('.', $file['name']);
            $ext = end($arrNameFile);
            $name = str_replace(".".$ext,"",$file['name']);
            $file_name = $name.''.time();
            $file_name =  $this->normalizeString($file_name);
            $draft_filename = $file_name. '.'. $ext;
            $downloadUrl = $file['downloadUrl'];
        }
        $this->saveFileFromUrl($downloadUrl, $draft_filename);
    }

    public function saveFileFromUrl($url, $fileName)
    {
        //Download the file using file_get_contents.
        $downloadedFileContents = file_get_contents($url);
         
        //Check to see if file_get_contents failed.
        if($downloadedFileContents === false){
            throw new Exception('Failed to download file at: ' . $url);
        }
        //Save the data using file_put_contents.
        // $save = file_put_contents($pathFileName, $downloadedFileContents);
        $save = Storage::put("public/uploads/files/".$fileName, $downloadedFileContents);
         
        //Check to see if it failed to save or not.
        if($save === false){
            throw new Exception('Failed to save file to: ' , $fileName);
        }
    }


    public function deleteDocument(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document == null) {
            return response()->json(['status' => false], 404);
        }

        $document->delete();

        return response()->json(['status' => true], 200);
    }

    public function deleteDocumentDetail(Request $request)
    {
        $document_id = $request['document_id'];
        $documents_detail = DocumentDetail::whereIn('document_id', $document_id)->delete();
        $documents = Document::whereIn('document_id', $document_id)->delete();

        // if ($documents == null) {
        //     return response()->json(['status' => false], 404);
        // }

        // $documents->delete();

        return response()->json(['status' => true], 200);
    }


    public function documentSetting(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document == null) {
            return response(null, 404);
        }

        if ($request->doc_folder_id) {
            $document->doc_folder_id = $request->doc_folder_id;
        }

        if ($request->expiration_days) {
            $document->expiration_days = $request->expiration_days;
        }

        if ($request->action) {
            $document->action = $request->action;
        } else {
            $document->action = 'sign';
        }

        if ($request->comment) {
            $document->comment = $request->comment;
        }

        if ($request->password) {
            $document->password = bcrypt($request->password);
        }

        $document->save();

        return response(null, 200);
    }

    public function getSignRequests(Request $request, $doc_id)
    {
        $srs = SignRequest::where('document_id', $doc_id)->get();
        if ($srs == null) {
            return response(null, 404);
        }

        return response()->json([
            'sign_requests' => $srs
        ], 200);
    }

    public function addSignRequest(Request $request, $doc_id)
    {
        $validator = Validator::make($request->all(), [
            'signer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $sr = new SignRequest([
            'document_id' => $doc_id,
            'signer_id' => $request->signer_id,
            'comment' => $request->comment
        ]);
        if ($request->request_type) {
            $sr->request_type = $request->request_type;
        } else {
            $sr->request_type = 'sign';
        }

        if ($request->expiration_days) {
            $sr->expiration_days = $request->expiration_days;
        } else {
            $sr->expiration_days = 14;
        }

        if ($request->password) {
            $sr->password = bcrypt($request->password);
        }

        $sr->save();

        return response()->json(null, 201);
    }

    public function editSignRequest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
            'signer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $sr = SignRequest::find($id);
        if ($request->request_type) {
            $sr->request_type = $request->request_type;
        } else {
            $sr->request_type = 'sign';
        }

        if ($request->expiration_days) {
            $sr->expiration_days = $request->expiration_days;
        } else {
            $sr->expiration_days = 14;
        }

        if ($request->password) {
            $sr->password = bcrypt($request->password);
        }

        $sr->save();

        return response()->json(null, 200);
    }

    public function deleteSignRequest($id)
    {
        $sr = SignRequest::find($id);
        if ($sr == null) {
            return response(null, 404);
        }

        $sr->delete();

        return response(null, 200);
    }

    public function getDocumentByID(Request $request){
        $document_id = $request->document_id;
        $show_image = 0;
        if(isset($request->show_image)){
            $show_image = $request->show_image;
        }
        $list = Document::where("document_id",$document_id)->with(['recipients','document_action'])->get();
        foreach ($list as $document) {
            if($show_image == 1) {
                $images = [];
                $objPDF = new PDFsController();
                $images = $objPDF->createObjImage($document->document_file, $document->document_id);
                $document['images'] = $images;
            }
            $doc_url = Storage::url($document->document_file); 
            $fileSize = File::size(public_path($doc_url));  
            $document['size'] = round($fileSize, 1);
            $document->document_file = asset($doc_url);     
        }
        
        return response()->json([
            'status' => true,
            'list'   => $list,
        ],200);
    }

    public function getDocumentByClientID(Request $request){
        $per_page = isset($request->per_page) ? $request->per_page : 10;
        $user_id = Auth::id();
        $keyword_document = !empty($request->search) ? $request->search : null;
        if ($request->status === "Status" || $request->status === "All") {
            $status = null;
        } else {
            $status = strtolower($request->status);
        }
        $date = null;
        switch ($request->date) {
            case "Date":
                $date = null;
                break;
            case "Last 24 Hours":
                $date = Carbon::today()->subDays(1);
                break;
            case "Last Week":
                $date = Carbon::today()->subDays(7);
                break;
            case "Last 30 Days":
                $date = Carbon::today()->subDays(30);
                break;
            case "Last 6 Months":
                $date = Carbon::today()->subMonths(6);
                break;
            case "Custom":
                $date = null;
                break;
            default:
                // code...
                break;
        }
        $where_all = [['creator_id','=',$user_id]];
        if(isset($keyword_document) && !empty($keyword_document)) {
            array_push($where_all, ['name','like','%'.$keyword_document.'%']);
        }
        if(isset($status) && !empty($status)) {
            array_push($where_all, ['status','=', $status]);
        }
        if(isset($date) && !empty($date)) {
            array_push($where_all, ['updated_at', '>=', $date]);
        }
        $list = DocumentDetail::with(['documents.recipients.user','documents.recipients'])
                                ->where($where_all)
                                ->paginate($per_page); 

        foreach ($list as $key => $document_detail) {
            $document_detail['amount'] = sizeof($document_detail['documents']);
            if(isset($document_detail['documents'][0]['recipients']))
                $document_detail['recipients'] = $document_detail['documents'][0]['recipients'];
            else
                $document_detail['recipients'] = [];
            unset($list[$key]['documents']);
        }
        return response()->json([
            'status' => true,
            'list'   => $list,
        ],200);
    }

    public static function normalizeString ($str = '')
    {
        $str = strip_tags($str); 
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }

    public function addRotatePage(Request $request){
        $data = $request->all();
        $document_action = DocumentAction::where([['document_id','=',$data['document_id']],['page','=',$data['page']]])->first();
        if(!isset($document_action )){
            $document_action = new DocumentAction();
        }
        foreach ($data as $key => $value) {
            $document_action[$key] = $value;
        }
        // return $document_action;
        $document_action->save();
        return response()->json([
            'status' => true
        ],200);
    }

    public function adDeletePage(Request $request){
        $data = $request->all();
        $document_action = DocumentAction::where([['document_id','=',$data['document_id']],['page','=',$data['page']]])->first();
        if(!isset($document_action )){
            $document_action = new DocumentAction();
        }
        foreach ($data as $key => $value) {
            $document_action[$key] = $value;
        }
        $document_action->save();
        return response()->json([
            'status' => true
        ],200);
    }
}
