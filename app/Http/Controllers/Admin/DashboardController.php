<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){

        $data=[];

        $data['CountPlans']=Plan::count();


        $data['CountUsers']=User::count();

        $data['CountSubs']=Subscription::Active()->count();

        $data['CountCancelSubs']=Subscription::NotActive()->count();

        $data['CountPendingSubs']=Subscription::Pending()->count();

        $data['CountPausedSubs']=Subscription::Paused()->count();


        $data['users']=User::limit(5)->OrderByDesc('id')->get();





        return view('Admin.dashboard',$data);
     }



}