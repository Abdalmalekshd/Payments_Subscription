<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/', function () {
    return view('welcome');
});



Route::group(['namespace'=>'App\Http\Controllers','middleware'=>'guest:web'],function(){

    //Start Login Route

    Route::get('signup','LoginController@signup')->name('user.signup');

    Route::post('user_signup','LoginController@usersignup')->name('user.createacc');


    Route::get('login','LoginController@login')->name('login');

    Route::post('user_Login','LoginController@userlogin')->name('user.signin');




    //End Login Route


});

Route::get('/auth/shopify', [ShopifyController::class, 'redirectToShopify'])->name('shopify.auth');

Route::get('/auth/shopify/callback', [ShopifyController::class, 'handleShopifyCallback'])->name('shopify.callback');


Route::group(['middleware' => ['auth:web']],function(){

Route::get('account',[AccountController::class,'account'])->name('home');

Route::post('updateAccount',[AccountController::class,'UpdateAccount'])->name('UpdateAccount');


Route::get('subscriptions',[AccountController::class,'subscriptions'])->name('subscriptions');



Route::get('update_payments',[AccountController::class,'update_payments'])->name('update_payments');




Route::get('cancel_sub',[AccountController::class,'cancel_sub'])->name('cancel_sub');



Route::post('/checkout', [PaymentController::class,'checkout'])->name('process.payment');

Route::get('success_url',  [PaymentController::class,'success'])->name('success');

Route::get('cancel_url',[PaymentController::class,'cancel'])->name('cancel');


Route::post('cancel_subscription',[PaymentController::class,'cancel_subscription'])->name('cancel.subscription');

Route::post('/subscription/pause', [PaymentController::class, 'pause'])->name('subscription.pause');
Route::post('/subscription/resume', [PaymentController::class, 'resume'])->name('subscription.resume');


});
