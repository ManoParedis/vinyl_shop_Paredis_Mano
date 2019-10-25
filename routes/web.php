<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
    //return 'The Vinyl Shop';
});

Route::get("contact-us", function (){
   return view("contact");
});*/
Route::view('/', 'home');
Route::get('shop', 'ShopController@index');
Route::get('shop/{id}', 'ShopController@show');
Route::view('contact-us', 'contact');
Route::get('shop_alt','ShopController@index_alt');
Route::prefix('admin')->group(function(){
    Route::redirect('/', 'records');
    Route::get('records', 'Admin\RecordController@index');
});
