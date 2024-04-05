<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => 1 // set user role_id to 1 which is admin

        ]);
        return response(new UserResource($user), Response::HTTP_CREATED);
    }


    public function login(LoginRequest $request) {
        if(!Auth::attempt($request->only('email', 'password'))){
            return \response([
                'error' => 'Invaild Credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user*/
        // user is retrieved
        $user = Auth::user();

        // create and retriev user token
        $token = $user->createToken('token')->plainTextToken;

        // to avoid sending the token to the fontend i used cookie
        // cookie leave for one day 60 * 24
        //  Make Auth with HttpOnly Cookies
        // in config/cors change supports_credentials to true
        // overide the handel method, manually add the jwt into headers Middleware/Authenticate
        $cookie = cookie('jwt', $token, 60 * 24);

        return \response([
            'jwt' => $token
        ])->withCookie($cookie);
    }


    // get the auth user
    public function user(Request $request) {
        $user = $request->user();
        return new UserResource($user->load('role'));
    }

    // logout function
    public function logout() {
        // delete cookie
        // $cookie = Cookie::queue(Cookie::forget('jwt'));
        $cookie = \Cookie::forget('jwt');

        // the only way to delete cookies is setting the expirering date in the past.
        return \response([
            'message' => 'logout was success.'
        ])->withCookie($cookie);
    }

    public function updateInfo(UpdateInfoRequest $request){
         //get user from the request
         // loggedin user
         $user = $request->user();

         // by using only we dont change the first name
         // if first_name is no provided it will show last_name and email
         $user->update($request->only('first_name', 'last_name', 'email'));

         return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request){
        //get user from the request
        // loggedin user
        $user = $request->user();

        // by using only we dont change the first name
        // if first_name is no provided it will show last_name and email
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
   }
}