<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AuthLink;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        if(!$user){
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
        
        if($request->password){
            if(!Hash::check($fields['password'], $user->password)){
                return response([
                    'message' => 'Bad creds'
                ], 401);
            }
        }else{
            try {
                // Check authorization
                if($user->email_auth_authorized){
                    $rdm_token = Str::random(30);
                    $email_auth = DB::table('email_auth')->insert(['email' => $user->email, 'token' => $rdm_token, 'expires_at' => Carbon::now()->addMinutes(15)]);
                }else{
                    return response([
                        'message' => 'You are not authorize to login only by email'
                    ], 401);
                }
                $user->auth_token = $rdm_token;
    
                // Send authentication link
                $user->notify(new AuthLink($user));
    
                return response([
                    'message' => 'A authentication link was send to you email address'
                ], 201);
    
            } catch (\Throwable $th) {
                //throw $th;
                return response([
                    'message' => $th->getMessage()
                ], 500);
            }
        }

        $token = $user->createToken('myapptoken');
        $user->token = $token->accessToken;

        return response($user, 201);
    }

    public function authLink(Request $request, $token){
        $email_auth = DB::table('email_auth')->where('token', $request->token)->first();

        if(!$email_auth || Carbon::now()->greaterThan($email_auth->expires_at)){
            return response([
                'message' => 'This authentication link is invalid'
            ], 401);
        }
        // Get user
        $user = User::where('email', $email_auth->email)->first();

        $token = $user->createToken('myapptoken');
        $user->token = $token->accessToken;

        return response($user, 201);
    }
}
