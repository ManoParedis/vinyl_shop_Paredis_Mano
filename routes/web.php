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

//shop
Route::view('/', 'home');
Route::get('shop', 'ShopController@index');
Route::get('shop/{id}', 'ShopController@show');
Route::get('shop_alt','ShopController@index_alt');

//contact
Route::get('contact-us', 'ContactUsController@show');
Route::get('contact', function () {
    $me = ['name' => env('MAIL_FROM_NAME')];
    return view('contact', $me);
});
Route::post('contact-us', 'ContactUsController@sendEmail');

//admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    route::redirect('/', 'records');
    Route::get('records', 'Admin\RecordController@index');
});
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    route::redirect('/', 'records');
    Route::get('records', 'Admin\RecordController@index');
});

//user
Route::redirect('user', '/user/profile');
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('profile', 'User\ProfileController@edit');
    Route::post('profile', 'User\ProfileController@update');
});


//password
Route::redirect('user', '/user/profile');
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('profile', 'User\ProfileController@edit');
    Route::post('profile', 'User\ProfileController@update');
    Route::get('password', 'User\PasswordController@edit');
    Route::post('password', 'User\PasswordController@update');
});

//admin Genre + Record + users
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    route::redirect('/', 'records');
    Route::get('genres/qryGenres', 'Admin\GenreController@qryGenres');
    Route::resource('genres', 'Admin\GenreController');
    Route::resource('records', 'Admin\RecordController');
    Route::get('users/qryUsers', 'Admin\UserController@qryUsers');
    Route::resource('users', 'Admin\UserController');
});

//auth + home
Auth::routes();
Route::view("/","home");
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout', 'Auth\LoginController@logout');
