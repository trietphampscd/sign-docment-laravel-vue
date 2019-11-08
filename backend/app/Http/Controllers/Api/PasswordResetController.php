<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\User;
use App\PasswordReset;

use App\Mail\SendMail;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'errors' => [
                    'email' => ["There is no account with this Email address."]
                ]
            ], 404);
        }

        $token = str_random(60);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token
            ]
        );

        if ($user && $passwordReset) {
            $data = array(
                'view' => 'emails.password.create',
                'address' => $user->email,
                'subject' => 'CoffeeSign, Reset password request',
                'token' => $token,
            );
    
            Mail::to($user->email)->send(new SendMail($data));
        }
        
        return response()->json([
            'message' => 'We have e-mailed your password reset link! '
        ], 201);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'errors' => ['This password reset token is invalid.']
            ], 404);

        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'errors' => ['This password reset token is expired.']
            ], 422);
        }

        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'errors' => ['This password reset token is invalid.']
            ], 404);
        }
        
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json([
                'errors' => ["We can't find a user with that Email address."]
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();

        $data = array(
            'view' => 'emails.password.success',
            'address' => $user->email,
            'subject' => 'CoffeeSign, Reset password successed'
        );
        Mail::to($user->email)->send(new SendMail($data));
        
        return response()->json($user);
    }
}
