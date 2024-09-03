<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Product;
use App\Models\Product_Plan;
use App\Models\User;


Route::get('/test', function () {
    return 'This route does not require authentication.';
});


Route::get('/invoice/{id}', [SubscriptionController::class, 'show'])->name('subscriptions.show');

Route::get('/startcheckout', [PaymentController::class, 'EmailtoProcessPayment'])->name('email.to.process.payment');

Route::post('/startverifycheckout', [PaymentController::class, 'processPayment'])->name('start.verify.payment');

Route::post('/proccess_checkout/{customer_id}', [PaymentController::class, 'checkout'])->name('process.checkout.payment');


Route::get('success_url',  [PaymentController::class,'success'])->name('success');

Route::get('cancel_url',[PaymentController::class,'cancel'])->name('cancel');