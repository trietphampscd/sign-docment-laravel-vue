<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use Auth;
use Validator;

use App\User;
use App\Department;
use App\CompanySize;
use App\Industries;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $list = User::with('client.user')->where("admin","0")->get();
        $listDepartment = Department::all();
        $listIndustries = Industries::all();
        $listCompanySize = CompanySize::select('id', DB::raw("CONCAT(size_from,'-',size_to) AS size"))->get();
        return view('admin.user.all', ['status'=>null, 'msg'=>null,
                    'list'=>$list, 'listDepartment' => $listDepartment,
                    'listIndustries' => $listIndustries,
                    'listCompanySize' => $listCompanySize]);
    }

    public function indexAdminUser(Request $request)
    {
        $list = User::with('client.user')->where("admin","1")->get();
        return view('admin.admin_users.all', ['status'=>null, 'msg'=>null,
                    'list'=>$list]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(isset($user->avatar)){
            Storage::disk('public')->delete(str_replace("/storage/","",$user->avatar));
        }
        $user->delete();
        return redirect()->back()->with(['status'=>'success','msg'=>'Delete successfully !']);
    }


    // Admin
    public function settings()
    {
        return view('pages/settings');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'password_old' => 'required|string',
            'password_new' => 'required|string',
            'password_confirm' => 'required|same:password_new',
        ]);

        if ($validator->fails()) 
        {          
            return redirect()->back()->with(['status'=>'error','msg'=>'Password or email incorrect!']);
        }
        
        $user = $request->user();
        $password_old = $request->password_old;

        if(Hash::check($password_old,$user->password))
        {
            $user = User::find($user->id);
            $user->password = bcrypt($request->password_new);
            $user->save();
            return redirect('/admin/logout');
        } 
        return redirect()->back()->with(['status'=>'error','msg'=>'Error password !']);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('admin.profile.view', ['status'=>null, 'msg'=>null, 'user'=>$user]);
    }

    public function profileViewEdit(Request $request)
    {
        $user = Auth::user();
        return view('admin.profile.edit', ['status'=>null, 'msg'=>null, 'user'=>$user]);
    }

    public function profileEdit(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        if ($files = $request->file('image')) 
        {
            $destinationPath = 'public/images/avatar';
            $profileImage = date('d-m-Y-His') . "_" . $files->getClientOriginalName();
            $path = $files->storeAs($destinationPath, $profileImage);
            $url = Storage::url($path);
            $user->avatar = asset($url);
        }
        $user->save();
        return redirect()->back()->with(['status'=>'success', 'msg'=>'Update successfully!']); 
    }

    public function newUser(Request $request)
    {
        try 
        {
            $data = $request->all();
            $validator = Validator::make($request->all()['user'], [
                'email' => 'required|string|unique:users'
            ]);
            
            if ($validator->fails()) 
            {   
                $request->flash();       
                return redirect()->back()->with(['status'=>'error','msg'=>'Email already exists !']);
            }

            $user = new User();
            $data['user']['password'] = Hash::make($request->password);

            foreach ($data['user'] as $key => $value) 
            {
                $user[$key] = $value;
            }

            if ($files = $request->file('image'))
            {
                $destinationPath = 'public/images/avatar';
                $profileImage = date('d-m-Y-His') . "_" . $files->getClientOriginalName();
                $path = $files->storeAs($destinationPath, $profileImage);
                $url = Storage::url($path);
                $user->avatar = asset($url);
            } 
            $user->active = true;
            $user->save();
            return redirect('/admin/users/all')->with(['status'=>'success', 'msg'=>"Add user successfully !"]);
        } catch (\Throwable $th) {
            dd($th);
            return redirect('/admin/users/all')->with(['status'=>'error', 'msg'=>"Add user error !"]);
        }
    }

    public function newUserAdmin(Request $request)
    {
        try 
        {
            $data = $request->all();
            $validator = Validator::make($request->all()['user'], [
                'email' => 'required|string|unique:users'
            ]);
            
            if ($validator->fails()) 
            {   
                $request->flash();       
                return redirect()->back()->with(['status'=>'error','msg'=>'Email already exists !']);
            }
            $user = new User();
            $data['user']['password'] = Hash::make($request->password);
            foreach ($data['user'] as $key => $value) 
            {
                $user[$key] = $value;
            }

            if ($files = $request->file('image'))
            {
                $destinationPath = 'public/images/avatar';
                $profileImage = date('d-m-Y-His') . "_" . $files->getClientOriginalName();
                $path = $files->storeAs($destinationPath, $profileImage);
                $url = Storage::url($path);
                $user->avatar = $url;
            } 
            $user->active = true;
            $user->admin = 1;
            $user->save();
            return redirect('/admin/admin-users/all')->with(['status'=>'success', 'msg'=>"Add user successfully !"]);
        } catch (\Throwable $th) {
            dd($th);
            return redirect('/admin/admin-users/all')->with(['status'=>'error', 'msg'=>"Add user error !"]);
        }
    }

    public function getUserEdit(Request $request){
        try {
            $id = $request->id;
            $user = User::find($id);
            return view('admin.user.edit')->with(['status' => null, 'msg' => null, 'user' => $user]);
        } catch (\Throwable $th) {
           return redirect()->back()->with(['status'=>'error', 'msg'=>'Not found user']);
        }
    }

    public function getUserAdminEdit(Request $request){
        try {
            $id = $request->id;
            $user = User::find($id);
            return view('admin.admin_users.edit')->with(['status' => null, 'msg' => null, 'user' => $user]);
        } catch (\Throwable $th) {
           return redirect()->back()->with(['status'=>'error', 'msg'=>'Not found user']);
        }
    }
  
    public function updateUser(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($request->all()['user'], [
                'email' => 'required|string|unique:users,email,'.$request->id
            ]);
            
            if ($validator->fails()) 
            {   
                $request->flash();       
                return redirect()->back()->with(['status'=>'error','msg'=>'Email already exists !']);
            }

            $user = User::find($request->id);
            if($data['password'] != null){
                $user->password = Hash::make($data['password']);
            }

            foreach ($data['user'] as $key => $value) 
            {
                $user[$key] = $value;
            }

            if ($files = $request->file('image'))
            {
                Storage::disk('public')->delete($user['avatar']);
                $destinationPath = 'public/images/avatar';
                $profileImage = date('d-m-Y-His') . "_" . $files->getClientOriginalName();
                $path = $files->storeAs($destinationPath, $profileImage);
                $url = Storage::url($path);
                $user->avatar = asset($url);
            } 
            
            $user->save();  
            return redirect('/admin/users/all')->with(['status'=>'success', 'msg'=> "Edit user successfully !"]);
        } catch (\Throwable $th) {
            return $th;
            return redirect('/admin/users/all')->with(['status'=>'error', 'msg'=> "Edit error!"]);
        }
    }

    public function updateUserAdmin(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($request->all()['user'], [
                'email' => 'required|string|unique:users,email,'.$request->id
            ]);
            
            if ($validator->fails()) 
            {   
                $request->flash();       
                return redirect()->back()->with(['status'=>'error','msg'=>'Email already exists !']);
            }

            $user = User::find($request->id);
            if($data['password'] != null){
                $user->password = Hash::make($data['password']);
            }

            foreach ($data['user'] as $key => $value) 
            {
                $user[$key] = $value;
            }

            if ($files = $request->file('image'))
            {
                Storage::disk('public')->delete($user['avatar']);
                $destinationPath = 'public/images/avatar';
                $profileImage = date('d-m-Y-His') . "_" . $files->getClientOriginalName();
                $path = $files->storeAs($destinationPath, $profileImage);
                $url = Storage::url($path);
                $user->avatar = $url;
            } 
            
            $user->save();  
            return redirect('/admin/admin-users/all')->with(['status'=>'success', 'msg'=> "Edit user successfully !"]);
        } catch (\Throwable $th) {
            return redirect('/admin/admin-users/all')->with(['status'=>'error', 'msg'=> "Edit error!"]);
        }
    }    
}
