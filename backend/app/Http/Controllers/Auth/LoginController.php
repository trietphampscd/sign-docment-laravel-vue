<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $remember = ($request->input('remember')) ? true : false;
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $remember)) {
            if(Auth::user()->admin == 1 || Auth::user()->admin == 2){
                return redirect()->intended('/admin/dashboard');
            }else{
                Auth::logout();
                return view('admin.auth.login', ['msg' => 'Incorrect username or password!']);
            }
        } 
        $request->flash();
        return view('admin.auth.login', ['msg' => 'Incorrect username or password!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
