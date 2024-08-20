<?php

    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\SubscribePurchaseController;
use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\ShopifyController;
    use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseController;

    Route::group(['namespace'=>'App\Http\Controllers','middleware'=>'guest:web'],function(){

    //Start SignUp Routes

    Route::get('signup','LoginController@signup')->name('user.signup');

    Route::post('user_signup','LoginController@usersignup')->name('user.createacc');
    //End SignUp Routes


    //Start Login Routes

    Route::get('login','LoginController@login')->name('login');

    Route::post('user_Login','LoginController@userlogin')->name('user.signin');

    //End Login Routes




    });
    //Start Shopify Routes

    Route::get('/auth/shopify', [ShopifyController::class, 'redirectToShopify'])->name('shopify.auth');

    Route::get('/auth/shopify/callback', [ShopifyController::class, 'handleShopifyCallback'])->name('shopify.callback');

    //End Shopify Routes


    Route::group(['middleware' => ['auth:web']],function(){


        //Start Products And Purchases Routes



        Route::get('products',[ProductsController::class,'products'])->name('home');

        Route::get('addproducts',[ProductsController::class,'AddProductsForm'])->name('Add.products');

        Route::post('createproducts',[ProductsController::class,'createproducts'])->name('Create.products');



    Route::get('manage_proddutcs_subs',[ProductsController::class,'manageProductssubs'])->name('product.subscription');


    //Start Subscribe Purchase  Routes
    Route::post('/Subscribe_Purchase', [PurchaseController::class,'SubscribePurchaseProduct'])->name('Purchase');

    Route::get('success_Subscribe_Purchase',  [PurchaseController::class,'PurchaseProductSuccess'])->name('Purchase.Success');

    Route::get('cancel_Subscribe_Purchase',[PurchaseController::class,'PurchaseProductCancel'])->name('Purchase.Cancel');
    //End Subscribe Purchase  Routes

    //End Products And Purchases Routes

    //Start Account Routes

    Route::get('account',[AccountController::class,'account'])->name('account');

    Route::post('updateAccount',[AccountController::class,'UpdateAccount'])->name('UpdateAccount');
    //End Account Routes


    //Start subscriptions Routes

    Route::get('subscriptions',[AccountController::class,'subscriptions'])->name('subscriptions');
    //End subscriptions Routes


    //Start receipts Routes

    Route::get('receipts',[AccountController::class,'receipts'])->name('receipts');

    //End receipts Routes



    //Start update payments Routes

    Route::get('update_payments',[AccountController::class,'update_payments'])->name('update_payments');



    Route::post('/update-card', [AccountController::class, 'updateCard'])->name('update.card');

    //End update payments Routes


    //Start cancel sub Routes

    Route::get('cancel_sub',[AccountController::class,'cancel_sub'])->name('cancel_sub');
    //End cancel sub Routes


    //Start Stripe Payment Routes

    Route::post('/checkout', [PaymentController::class,'checkout'])->name('process.payment');

    Route::get('success_url',  [PaymentController::class,'success'])->name('success');

    Route::get('cancel_url',[PaymentController::class,'cancel'])->name('cancel');

    //End Stripe Payment Routes


    //Start Cancel && Pause && Resume subscription Routes

    Route::post('cancel_subscription',[PaymentController::class,'cancel_subscription'])->name('cancel.subscription');

    Route::post('/subscription/pause', [PaymentController::class, 'pause'])->name('subscription.pause');
    Route::post('/subscription/resume', [PaymentController::class, 'resume'])->name('subscription.resume');


    //End Cancel && Pause && Resume subscription Routes


    });
