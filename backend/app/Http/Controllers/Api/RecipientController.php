<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Recipient;

use App\Document;
use App\User;
use App\DocumentRecipient;
use App\DocumentDetail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMailRecipient;
use Mail;
use Request as rq;
use Ixudra\Curl\Facades\Curl;



class RecipientController extends Controller
{
    public function addRecipient(Request $request){
        $data =  $request->all();
        $list = []; 
        foreach ($data['recipients'] as $recipient) {
            if($recipient['com_type']) {
                $recipient['phone'] = null; 
                $recipient['nickname'] = null;
                $recipient['uuid'] = null;
            } else {
                $recipient['email'] = null;
            }
            $recipient['set_password'] ? $recipient['password'] = $recipient['password'] : $recipient['password'] = null;
            unset($recipient['sign_type_name']); 
            unset($recipient['com_type']);
            unset($recipient['set_password']);
            unset($recipient['confirm_password']);
            if(isset($recipient['id'])){
                $obj_recipient = Recipient::find($recipient['id']);
                unset($recipient['id']);
            }else{
                $obj_recipient = new Recipient();
            }

            foreach ($recipient as $key => $value) {
                $obj_recipient[$key] = $value;
            }

            // $obj_recipient['client_id'] = 1;
            $obj_recipient->save();
            array_push($list,$obj_recipient);

            foreach($data['documents'] as $document_id){
                $obj = DocumentRecipient::where(
                        [
                            ['recipient_id', '=', $obj_recipient->id],
                            ['document_id', '=', $document_id['id']]
                        ])->first();
                if(is_null($obj)){
                    $obj = new DocumentRecipient();
                }
                $obj->recipient_id = $obj_recipient->id;
                $obj->document_id  = $document_id['id'];
                $obj->save();
            }
        }

        return response()->json([
            'status' => true,
            'list'   => $list,
        ],200);
    }

    public function deleteRecipient(Request $request){
        $check = Recipient::find($request->id)->delete();
        if($check){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }
    
    public function getRecipientByDocumentId(Request $request){
        $document_id = $request->document_id;
        $obj = Document::where("document_id",$document_id)
                ->with(['recipients'=>function($query){
                        $query->where('action','<>','copy');
                }])->first();
        $list = $obj['recipients'];
        return response()->json([
            'status' => true,
            'list'   => $list,
        ],200);        
    }

    public function sentEmailReview(Request $request){
        $data = $request->all();
        $documents = Document::where('document_id','=',$data['document_id'])->get();
        foreach ($documents as $document) {
            $document->expiration_days = $data['expiration'];
            $document->save();
        }
        $recipients = $data['recipients'];
        $info['subject'] = $data['subject'];
        $info['message'] = $data['message'];
        $info['url_root'] = rq::root();
        $info['recipients'] = [];

        foreach ($recipients as $key => $recipient) {
            if($recipient['action'] != 'copy' && empty($recipient['uuid'])){
                array_push($info['recipients'], $recipient);
            }
        }
        // $info['document_path'] = storage_path('uploads/documents/sample1567588556.pdf');
        foreach ($recipients as $key => $recipient) {
            if(empty($recipient['uuid'])) {
                if($recipient['action'] == 'copy' && $recipient['email'] != null) {
                    try {
                        $url = rq::root().'/api/pdf/export?recipient_id='.$recipient['id'].'&document_id='.$data['document_id'];
                        $info['url_document'] = $url;
                        Mail::to($recipient['email'] )->send(new SendMailRecipient($info));
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => false,
                            'msg' => $th
                        ]);
                    }
                }else{
                    try {
                        $url = rq::root().'/api/pdf/export?recipient_id='.$recipient['id'].'&document_id='.$data['document_id'];
                        $info['url_document'] = $url;
                        Mail::to($recipient['email'] )->send(new SendMailRecipient($info));
                        // break;
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => false,
                            'msg' => $th
                        ]);
                    }
                }
            } else {
                $uuid = $recipient['uuid'];
                $response = Curl::to('https://kapi.kakao.com/v1/api/talk/friends/message/default/send')
                ->withHeader('Authorization: Bearer '.$data['access_token'])
                ->withContentType('application/x-www-form-urlencoded')
                ->withData( array( 
                    // 'receiver_uuids' => '["CDkNOw47CD4GNRkqGy8aKx4tHy0BNAUzBjVt"]',
                    'receiver_uuids' => '["'.$uuid.'"]',
                    'template_object' => '{
                               "object_type": "text",
                                "text": "Test Sharing via Kakao Talk",
                                "link": {
                                    "web_url": "https://developers.kakao.com",
                                    "mobile_web_url": "https://developers.kakao.com"
                                },
                                "button_title": "Checking and Signing Content"
                            }' ) 
                )
                ->post();
            }
        }

        //add documentdetail
        $obj = new DocumentDetail();
        $obj->document_id = $data['document_id'];
        $obj->name = $data['name'];
        $obj->creator_id = $request->user()->id;
        $obj->expiration_days = $data['expiration'];
        $obj->action = 'sign';
        $obj->status = 'waiting';
        $obj->save();
    
        return response()->json([
            'status' => true,
        ]);
    }

    public function kakaoFriends(Request $request) 
    {
        $response = Curl::to('https://kapi.kakao.com/v1/api/talk/profile')
        ->withHeader('Authorization: Bearer '.$request['access_token'])
        ->get();

        echo "Start <br/>"; echo '<pre>'; print_r($response);echo '</pre>';exit("End Data");
    }

}
