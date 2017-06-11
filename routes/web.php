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

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('ExactAuth', 'ExactAuth@GetData');

Route::get('ExactBack', "ExactBack@GetData");

Auth::routes();

Route::get('/', 'HomeController@index')->name('/');

Route::get('/dashboard', 'HomeController@dashboard')->name('/dashboard');

Route::get('/charts', 'HomeController@charts')->name('/charts');

Route::get('/bookkeeping', 'HomeController@bookkeeping')->name('/bookkeeping');

Route::get('abn', 'AbnController@getAccounts');
