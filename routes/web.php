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

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['prefix' => '/account', 'middleware' => ['auth']], function () {
    Route::get('/', 'Account\AccountController@index')->name('account.index');

    /* Account settings */
    Route::post('/update', 'Auth\ProfileController@updateProfileInfo')
        ->name('account.update');

    /* User manipulation */
    Route::group(['prefix' => '/users', 'middleware' => ['auth.access:admin']], function () {
        Route::get('/', 'Account\UserController@showUserListPage')
            ->name('account.users.index');
        Route::post('/update', 'Account\UserController@updateUserInfo')
            ->name('account.users.update');
        Route::get('/{id}/view', 'Account\UserController@viewUserPage')
            ->name('account.users.view')->where('id', '[0-9]+');
    });

    /* Project manipulation */
    Route::group(['prefix' => '/projects'], function () {
        Route::get('/', 'Account\ProjectController@showProjectsPage')
            ->name('account.projects.index');

        Route::get('/create', 'Account\ProjectController@showCreateProjectPage')
            ->name('account.projects.create');
        Route::post('/create', 'Account\ProjectController@createProject')
            ->name('account.projects.create.post');

        Route::post('/remove', 'Account\ProjectController@removeProject')
            ->name('account.projects.remove');

        Route::get('/{id}/view', 'Account\ProjectController@viewProjectDetailsPage')
            ->name('account.projects.view')
            ->where('id', '[0-9]+');

        Route::post('/run', 'Account\ProjectController@runProject')
            ->name('account.projects.run');

        Route::post('/{projectId}/upload/data', 'Account\ProjectController@uploadProjectData')
            ->name('account.projects.upload.data')
            ->where('projectId', '[0-9]+');

        Route::get('/frameworks', 'Account\ProjectController@getFrameworksList')
            ->name('account.projects.frameworks');
        Route::get('/args/{framework}/{command}', 'Account\ProjectController@getCommandOptions')
            ->name('account.projects.frameworks')
            ->where(['framework' => '[a-zA-Z_]+', 'command' => '[a-zA-Z_]+']);
    });
});