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

Route::group(['prefix' => '/account', 'middleware' => ['auth']], function () {
    Route::get('/', 'Account\AccountController@index')->name('account.index');

    Route::post('/update', 'Auth\ProfileController@updateProfileInfo')
        ->name('account.update');

    /* User manipulation */
    Route::group(['prefix' => '/users', 'middleware' => ['auth.access:admin']], function () {
        Route::get('/', 'Account\UserController@showUserListPage')
            ->name('account.users.index');
        Route::post('/update', 'Account\UserController@updateUserInfo')
            ->name('account.users.update');
        Route::get('/{id}', 'Account\UserController@viewUserPage')
            ->name('account.users.view')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => '/projects'], function () {
        Route::get('/', 'Account\ProjectController@showProjectsPage')
            ->name('account.projects.index');

        Route::get('/create', 'Account\ProjectController@showCreateProjectPage')
            ->name('account.projects.create');
        Route::post('/create', 'Account\ProjectController@createProject')
            ->name('account.projects.create.post');

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