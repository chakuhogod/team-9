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

Route::get('/login2', function () {
    return view('authentication.login');
});

Route::get('/register2', function () {
    return view('authentication.register');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/app', function () {
    return view('layouts.app');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('/');

Route::get('/dashboard', 'HomeController@index')->name('/');

Route::get('/charts', 'HomeController@index')->name('/');

Route::get('/settings', 'HomeController@index')->name('/');
