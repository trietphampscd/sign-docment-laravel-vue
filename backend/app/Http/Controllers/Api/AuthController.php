<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
// use GuzzleHttp\Client;
use JWTAuth;

use App\User;
use App\Client;

use Config;

use App\Mail\SendMail;
use App\Notifications\SignupActivate;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if ($request->provider_name) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $provider = $user->provider_name!=null ? $user->provider_name : "Email";
                return response()->json([
                    'errors' => ["email" => ["Your Email address has been already registered as ". $provider . " account"]]
                ], 422);
            }
        } else {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => str_random(60),
            'provider_name' => $request->provider_name
        ]);

        if ($request->provider_name) {
            $user['active'] = true;
            $user->save();

            return response()->json([
                'message' => 'Successfully signed up!'
            ], 201);
        }

        $user->save();

        $data = array(
            'view' => 'emails.auth.activate',
            'address' => $user->email,
            'subject' => 'CoffeeSign, Account confirmation',
            'activation_token' => $user->activation_token,
        );
        Mail::to($user->email)->send(new SendMail($data));

        return response()->json([
            'message' => 'Successfully signed up! Please confirm your Email to activate your account.'
        ], 201);
    }

    public function sendActivationToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $user = new User([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'activation_token' => str_random(60),
                'provider_name' => $request->provider_name
            ]);
    
            $user->save();
        }

        $data = array(
            'view' => 'emails.auth.activate',
            'address' => $user->email,
            'subject' => 'CoffeeSign, Account confirmation',
            'activation_token' => $user->activation_token,
        );
        Mail::to($user->email)->send(new SendMail($data));

        return response()->json([
            'message' => 'Successfully signed up! Please confirm your Email to activate your account.'
        ], 201);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'errors' => ['Sorry, but this activation token is invalid.']
            ], 404);
        }

        $user->active = true;
        $user->activation_token = null;

        $user->save();

        return $user;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($request->provider_name) {
            if (!$user) {
                return response()->json([
                    'errors' => [],
                ], 404);
            } else if ($request->provider_name != $user->provider_name) {
                $provider = $user->provider_name;
                if ($provider == null) {
                    $provider = "email";
                }
                return response()->json([
                    'errors' => ['email' => ["This email address is already registered with ". $provider. ' account']]
                ], 401);
            }
        }
        if (!$user && $request->provider_name) {
            return response()->json([
                'errors' => [],
            ], 404);
        }

        if (!$user) {
            return response()->json([
                'errors' => ["email" => ["This account doesn't exist"]]
            ], 401);
        } else if (!$user['active']) {
            return response()->json([
                'errors' => ["email" => ["This email hasn't verified yet"]]
            ], 401);
        }

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'errors' => ["password" => ["Password is incorrect"]]
            ], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }

    public function lineAuth(Request $request)
    {
        $app_url = urlencode(env('APP_URL', 'http://localhost:8000'));
        $client_url = env('CLIENT_URL', 'https://localhost:8080');

        $line_client_id = env('LINE_CLIENT_ID', '');
        $line_client_secret = env('LINE_CLIENT_SECRET', '');
        $line_scope = str_slug(env('LINE_SCOPE', ''), '%20');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code=". $request->code. "&redirect_uri=".$app_url."%2Fapi%2Fauth%2Flineauth&client_id=".$line_client_id."&client_secret=".$line_client_secret."&scope=".$line_scope."&nonce=09876xyz");

        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $json_result = json_decode($result);
        $a = base64_decode($json_result->id_token);
        $b = substr($a, strpos($a, "}") + 1, -1);
        $c = substr($b, 0, strpos($b, "}") + 1);
        $d = json_decode($c);

        $user = User::where('email', $d->email)->first();
        if($user) {     
          return redirect()->away($client_url.'/login/'.$d->email);
        }

        if (preg_match('/\s/',$d->name)) {
          $name = preg_split ('/\s/', $d->name);
          $first_name = $name[0];
          $last_name = $name[1];
        }
        else {
          $first_name = $d->name;
          $last_name = ' ';
        }

        $profile = new \Illuminate\Http\Request();
        $profile->setMethod('POST');
        $profile->request->add([
          'email' => $d->email,
          'password' => 'lCSpwd2@',
          'password_confirmation' => 'lCSpwd2@',
          'first_name' => $first_name,
          'last_name' => $last_name,
          'name' => $d->name
        ]);

        $validator = Validator::make($profile->all(), [
          'first_name' => 'required|string',
          'last_name' => 'required|string',
          'email' => 'required|string|email|unique:users',
          'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
          return redirect()->away($client_url.'/login/errors/'.$validator->errors());
          // return redirect()->away('https://localhost:8080/#/login/errors/'.$validator->errors());
        }

        $user = new User([
          'first_name' => $profile->first_name,
          'last_name' => $profile->last_name,
          'name' => $profile->name,
          'email' => $profile->email,
          'password' => bcrypt($profile->password),
          'activation_token' => str_random(60),
          'provider_name' => 'line'
        ]);

        $user['active'] = true;
        $user->save();
          
        return redirect()->away($client_url.'/login/'.$profile->email);
        // return redirect()->away('https://localhost:8080/#/login/'.$profile->email);
    }

    public function kakaoProfile(Request $request)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v2/user/me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array();
        $headers[] = 'Authorization: Bearer '. $request->access_token;
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // curl_setopt($ch, CURLOPT_POSTFIELDS, "propertyKeys=%5B%E2%80%9Ckakao_account.email%E2%80%9D%5D");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "propertyKeys=%5B%22kakao_account.email%22%2C%20%22name%22%2C%20%22first_name%22%2C%20%22last_name%22%5D");
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    public function user(Request $request){ 
        $user = User::with(['client' => function($query) {
          $query->leftJoin('company_sizes', 'clients.company_size_id', 'company_sizes.id')
                ->leftJoin('departments', 'clients.department_id', 'departments.id')
                ->leftJoin('industries', 'clients.industry_id', 'industries.id');
        }])->find($request->user()->id);
        if(!is_null($user)) {
          $user->avatar = $user->avatar ? asset(Storage::url($user->avatar)) : $user->avatar;
          return response()->json([
            'status' => true,
            'user' => $user
          ],200);
        }
        else {
          return response()->json([
            'status' => false
          ],404);
        }
    }
}
