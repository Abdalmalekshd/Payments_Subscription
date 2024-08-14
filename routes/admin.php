<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\PaymentController;



Route::group(['namespace'=>'App\Http\Controllers\Admin','middleware'=>'guest:admin','prefix'=>'admin'],function(){

    Route::get('adminlogin','AdminLoginController@login')->name('admin.login');

    Route::post('user_Login','AdminLoginController@adminlogin')->name('admin.signin');

});



Route::group(['namespace'=>'App\Http\Controllers\Admin','middleware'=>'auth:admin','prefix'=>'admin'],function(){

    Route::get('Dashboard','DashboardController@dashboard')->name('dashboard');

    Route::get('manage_subscription','DashboardController@managesubs')->name('manage.subs');

    Route::get('manage_users','DashboardController@manageusers')->name('manage.users');


    Route::get('delete_users/{id}','DashboardController@Deleteuser')->name('delete.user');


});