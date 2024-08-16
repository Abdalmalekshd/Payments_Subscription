<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ManageSubsController extends Controller
{
    public function managesubs(){

        $data=[];
        $data['Subs']=Subscription::with('User','Plan')->get();


        return view('admin.ManageSubs',$data);
     }
}