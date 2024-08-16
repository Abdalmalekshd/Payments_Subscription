<?php


use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'App\Http\Controllers\Admin','middleware'=>'guest:admin','prefix'=>'admin'],function(){

    Route::get('adminlogin','AdminLoginController@login')->name('admin.login');

    Route::post('admin_Login','AdminLoginController@adminlogin')->name('admin.signin');

});



Route::group(['namespace'=>'App\Http\Controllers\Admin','middleware'=>['auth:admin'],'prefix'=>'admin'],function(){
    //Start Dashboard Routes

    Route::get('Dashboard','DashboardController@dashboard')->name('dashboard');
    //End Dashboard Routes


    //Start Manage Subscription Routes

    Route::get('manage_subscription','ManageSubsController@managesubs')->name('manage.subs');
    //End Manage Subscription Routes

    //Start Manage User Routes
    Route::get('manage_users','ManageUserController@manageusers')->name('manage.users');

    Route::get('delete_users/{id}','ManageUserController@Deleteuser')->name('delete.user');
    //End Manage User Routes


    //Start Settings Routes

    Route::get('settings','SettingsController@settings')->name('admin.settings');


    Route::post('UpdateAdminAccount','SettingsController@UpdateAccount')->name('admin.update.account');


    //End Settings Routes


});