<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\EmailStyle;
use App\Client;

use Auth;

class EmailStylesController extends Controller
{
    public function createOrUpdateEmailSyle(Request $request){
        $user_id = Auth::id();
        $client = Client::with('user')->where("user_id",$user_id)->first();
        $data = $request->all();
        $data = json_decode($data['email_style'], true);
        $data['client_id'] = $client->id;
    
        if(isset($data['id'])){
            $email_style = EmailStyle::find($data['id']);
            unset($data['id']);
        }else{
            $email_style = new EmailStyle();
        }
       
        foreach ($data as $key => $value) {
            $email_style[$key] = $value;
        }

        if($file = $request->file('logo')){
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_name = $name.''.time();
            $draft_filename = $file_name. '.'. $ext;
            Storage::putFileAs('public/uploads/logo_images', $file, $draft_filename);
            Storage::disk('public')->delete($email_style['logo_image']);
            $email_style['logo_image'] = 'uploads/logo_images/'. $draft_filename;
        } 
        $email_style->save();

        return \response()->json([
            'status' => true
        ],200);
    }

    public function getEmailStyle(Request $request){
        $user_id = Auth::id();
        $client = Client::with('user')->where("user_id",$user_id)->first(); 
        $email_style = EmailStyle::where('client_id',$client->id)->first();
        return \response()->json([
            'status' => true,
            'email_style' => $email_style
        ],200);
    }

}
