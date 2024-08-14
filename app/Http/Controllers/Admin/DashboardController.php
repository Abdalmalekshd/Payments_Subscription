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





        return view('admin.dashboard',$data);
     }



    public function managesubs(){

        $data=[];
        $data['Subs']=Subscription::with('User','Plan')->get();







        return view('admin.ManageSubs',$data);
     }




    public function manageusers(){

        $data=[];
        $data['Users']=User::get();







        return view('admin.ManageUsers',$data);
     }
}
