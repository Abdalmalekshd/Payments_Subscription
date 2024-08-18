<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminProductsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageSubsController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>'guest:admin','prefix'=>'admin'],function(){

    Route::get('adminlogin',[AdminLoginController::class,'login'])->name('admin.login');

    Route::post('admin_Login',[AdminLoginController::class,'adminlogin'])->name('admin.signin');

});



Route::group(['middleware'=>['auth:admin'],'prefix'=>'admin'],function(){
    //Start Dashboard Routes

    Route::get('Dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    //End Dashboard Routes

    Route::get('addproducts',[AdminProductsController::class,'AddProductsForm'])->name('Add.products');

    Route::post('createproducts',[AdminProductsController::class,'createproducts'])->name('Create.products');

    Route::get('products',[AdminProductsController::class,'ShowProducts'])->name('show.products');


    Route::post('update_products',[AdminProductsController::class,'UpdateProducts'])->name('update.products');


    Route::get('delete_products/{id}',[AdminProductsController::class,'DeleteProducts'])->name('delete.products');

    //Start Manage Subscription Routes

    Route::get('manage_subscription',[ManageSubsController::class,'managesubs'])->name('manage.subs');

    Route::get('manage_products_subscription',[ManageSubsController::class,'manageProductssubs'])->name('manage.products.subs');

    //End Manage Subscription Routes

    //Start Manage User Routes
    Route::get('manage_users',[ManageUserController::class,'manageusers'])->name('manage.users');

    Route::get('delete_users/{id}',[ManageUserController::class,'Deleteuser'])->name('delete.user');
    //End Manage User Routes


    //Start Settings Routes

    Route::get('settings',[SettingsController::class,'settings'])->name('admin.settings');


    Route::post('UpdateAdminAccount',[SettingsController::class,'UpdateAccount'])->name('admin.update.account');


    //End Settings Routes


});