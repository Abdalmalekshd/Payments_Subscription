<?php
use App\Models\User;

Route::group(['namespace'=>'App\Http\Controllers','middleware'=>'guest:customer'],function(){

Route::get('test',function(){
    return User::with('customers')->get();

});
});