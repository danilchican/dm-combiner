<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->group(['prefix' => '/v1'], function ($api) {

        /* Authentication */
        $api->group(['prefix' => '/auth'], function ($api) {
            $api->post('/access/token', 'App\Http\Controllers\Api\Auth\LoginController@login');

            $api->group(['middleware' => 'jwt.auth'], function ($api) {
                $api->get('/profile', 'App\Http\Controllers\Api\Auth\ProfileController');
                $api->post('/logout', 'App\Http\Controllers\Api\Auth\LoginController@logout');
            });
        });

    });
});