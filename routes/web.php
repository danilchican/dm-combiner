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
})->name('index');

Route::auth();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => '/dashboard', 'middleware' => ['auth.access:admin']], function () {
    Route::get('/', 'Dashboard\DashboardController')->name('dashboard.index');
});

Route::group(['prefix' => '/account', 'middleware' => ['auth.access:client']], function () {
    Route::get('/', 'Account\AccountController')->name('account.index');
});