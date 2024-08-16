<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminAccountRequest;
use App\Models\Admin;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    public function settings(){
        $data=[];
        $data["admin"]=Admin::find(Auth::user()->id);
        return view('Admin.Settings',$data);

    }



    public function UpdateAccount(AdminAccountRequest $req)
    {
        $admin = Admin::find($req->id);


        if ($admin && $req->id == Auth::user()->id) {
            $admin->name = $req->name;

            $admin->email = $req->email;

            $admin->password = Hash::make($req->password);

            $admin->save();

            Auth::guard()->login($admin);

            return redirect()->route('admin.settings')->with(['success' => 'Admin account updated successfully']);
        }

    }



}