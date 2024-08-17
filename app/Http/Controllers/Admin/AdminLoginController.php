<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{

    public function login(){
        return view('admin.login');
     }


     public function adminlogin(Request $request){


         if(auth()->guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password])){
             return redirect()->intended(route('dashboard'));
         }

         return redirect()->back()->withInput($request->only('email'));
     }
}