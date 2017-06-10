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

Route::get('/', function () {
    return view('dashboard');
});

<<<<<<< HEAD
Route::get('admin', function () {
    return view('admin_template');
});


Route::get('Test', 'Test@GetData');

Route::get('ExactLogin', 'ExactLogin@GetData');

Route::get('ExactLoginDone', 'ExactLoginDone@GetData');

Route::get('ExactAddSale', 'ExactAddSale@GetData');
=======
Route::get('dashboard', function () {
    return view('dashboard');
});

Route::get('bookkeeping', function () {
    return view('bookkeeping');
});

Route::get('charts', function () {
    return view('charts');
});

Route::get('settings', function () {
    return view('settings');
});
>>>>>>> master
