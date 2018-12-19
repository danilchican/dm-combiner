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
    Route::get('/users/{id}', 'Dashboard\UserController@viewUserPage')
        ->name('dashboard.users.view')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/account', 'middleware' => ['auth.access:client']], function () {
    Route::get('/', 'Account\AccountController')->name('account.index');

    Route::group(['prefix' => '/projects'], function () {
        Route::get('/', 'Account\ProjectController@showProjectsPage')
            ->name('account.projects.index');
        Route::get('/create', 'Account\ProjectController@showCreateProjectPage')
            ->name('account.projects.create');
        Route::post('/{projectId}/upload/data', 'Account\ProjectController@uploadProjectData')
            ->name('account.projects.upload.data')
            ->where('projectId', '[0-9]+');
        Route::post('/create', 'Account\ProjectController@createProject')
            ->name('account.projects.create.post');
        Route::get('/frameworks', 'Account\ProjectController@getFrameworksList')
            ->name('account.projects.frameworks');
        Route::get('/args/{framework}/{command}', 'Account\ProjectController@getCommandOptions')
            ->name('account.projects.frameworks')
            ->where(['framework' => '[a-zA-Z_]+', 'command' => '[a-zA-Z_]+']);
    });
});