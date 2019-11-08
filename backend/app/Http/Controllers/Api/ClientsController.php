<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Client;
use App\User;
use App\Department;
use Auth;
use Validator;
use Hash;


class ClientsController extends Controller
{
    public function getClient(Request $request)
    {
        $user_id = Auth::id();
        $client = Client::with('user')->where("user_id",$user_id)->first();
        if(!is_null($client))
            return response()->json([
                'status' => true,
                'client' => $client
            ],200);
        else
            return response()->json([
                'status' => false
            ],404);
    }

    public function newClient(Request $request)
    {
        $data_client = $request->except(['first_name','last_name', 'department_name']);
        $client = Client::where("user_id", Auth::id())->first();

        if(is_null($client)){
            // $user = User::find(Auth::id())->first();
            $user = User::where('id',Auth::id()) -> first();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();
            $client = new Client();
            $client->user_id = Auth::id();

            if($request->account_type == "Business"){
                $deparment = Department::where('department_name',$request->department_name)->first();
                if(is_null($deparment)){
                    $deparment = new Department();
                    $deparment->department_name = $request->department_name;
                    $deparment->save();
                }
                $client->department_id = $deparment->id;

                foreach ($data_client as $key => $value) {
                    $client[$key] = $value;
                }
            }else{
                $client->account_type = $request->account_type;
            }

            $client->save();
            return response()->json([
                'status' => true
            ],200);        
        }else{
            return response()->json([
                'status' => false,
                'errors' => 'Client already exist !'
            ]);
        }
    }

    public function getAllEmailClient(Request $request){
        $user_id = Auth::id();
        $list = DB::table('users')->select('id', 'email')->get();
        return response()->json([
            'status' => true, 'list_email' => $list
        ], 200);
    }
    // not test
    public function updateProfile(Request $request){
        $data_client = $request->except(['first_name','last_name', 'department_name', 'id']);
        $client = Client::where("user_id", Auth::id())->first();
        if(is_null($client)){
            $client = new Client();
            $client->user_id = Auth::id();
        }
        //upddate userinfo
        $user = User::find(Auth::id());
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();
        //update client

        if($request->account_type == "Business"){
            $deparment = Department::where('department_name',$request->department_name)->first();
            if(is_null($deparment)){
                $deparment = new Department();
                $deparment->department_name = $request->department_name;
                $deparment->save();
            }
            $client->department_id = $deparment->id;

            foreach ($data_client as $key => $value) {
                $client[$key] = $value;
            }
        }else{
            $client->account_type = $request->account_type;
            $client->company_name = null;
            $client->company_size_id = null;
            $client->industry_id = null;
            $client->department_id = null;
        }
        $client->save();

        return response()->json([
            'client' => $client,
            'status' => false,
            'errors' => 'Client update done !'
        ]);
    }
    //ok
    public function changeAvatar(Request $request){
        $user = User::find($request->user()->id);
        $ext = $this->getExtentionBase64($request->avatar);
        $avatar = $request->avatar;  // your base64 encoded
        $avatar = str_replace('data:image/png;base64,', '', $avatar);
        $avatar = str_replace(' ', '+', $avatar);
        $avatarName = time().'.'.$ext;
        File::delete($user['avatar']);
        File::put('storage/img/avatars/' . $avatarName, base64_decode($avatar));
        $user['avatar'] = 'img/avatars/'. $avatarName;
        $user->save();
        $url = Storage::url($user->avatar);
        $user->avatar = asset($url);
        return response()->json([
            'user' => $user,
            'status' => true
        ],200);
    }
    //get extions file base64
    public function getExtentionBase64($data){
        $pos  = strpos($data, ';');
        $type = explode(':', substr($data, 0, $pos))[1];
        $ext = explode('/',$type)[1];
        return $ext;
    }
    //change password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'password_old' => 'required|string',
            'password_new' => 'required|string',
            'password_confirm' => 'required|same:password_new',
        ]);

        if ($validator->fails()) {
          return response()->json([
              'errors' => $validator->errors()->all()
          ], 422);
        }
        
        $user = $request->user();
        $password_old = $request->password_old;

        if(Hash::check($password_old,$user->password))
        {
            $user = User::find($user->id);
            $user->password = bcrypt($request->password_new);
            $user->save();

            return response()->json([
                'status' => true,
            ], 200);
        }
        else {
          return response()->json([
            'errors' => ["password" => ["Password is incorrect"]]
          ], 401);
        }
    }
}
