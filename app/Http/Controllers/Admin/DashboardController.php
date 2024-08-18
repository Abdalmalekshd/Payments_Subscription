<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){

        $data=[];
        $data['CountUsers']=User::get()->count();

        $data['CountSubs']=Subscription::Active()->get()->count();

        $data['CountCancelSubs']=Subscription::NotActive()->get()->count();

        $data['users']=User::limit(5)->OrderByDesc('id')->get();





        return view('Admin.dashboard',$data);
     }



}