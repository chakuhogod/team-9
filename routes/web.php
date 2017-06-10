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
    return view('welcome');
});

Route::get('admin', function () {
    return view('admin_template');
});


Route::get('Test', 'Test@GetData');

Route::get('ExactLogin', 'ExactLogin@GetData');

Route::get('ExactLoginDone', 'ExactLoginDone@GetData');

Route::get('ExactAddSale', 'ExactAddSale@GetData');