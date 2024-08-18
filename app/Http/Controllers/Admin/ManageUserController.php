<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{

    public function manageusers(){

        $data=[];
        $data['Users']=User::get();

        return view('Admin.ManageUsers',$data);
     }


     public function Deleteuser($id){
        $user=User::find($id);
        if($user)
        {
            $user->delete();
        }
        return redirect()->back();
     }
}