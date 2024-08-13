<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Hotel;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{

    public function signup(){
        return view('User.signup');
     }


     public function usersignup(UserRequest $request){

        $user =   User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'gender'  =>$request->gender,
            'phone'    =>$request->phone,

        ]);


        Auth::login($user);


        return redirect()->route('home')->with(['success' => 'You are regestired']);

     }


    public function login(){
       return view('User.signup');
    }






    public function userlogin(Request $request){
        if(auth()->guard('web')->attempt(['email'=>$request->email,'password'=>$request->password])){
            return redirect()->intended(route('home'));
        }
        return redirect()->back()->withInput($request->only('email'));
    }




    public function userlogout(){
        auth('web')->logout();

        return redirect()->route('user.login');

    }
}
