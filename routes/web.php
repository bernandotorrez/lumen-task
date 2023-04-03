<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', [
    'as' => 'register',
    'uses' => 'UserController@register'
]);

$router->post('/login', [
    'as' => 'login',
    'uses' => 'UserController@login'
]);

$router->post('/refresh', [
    'as' => 'refresh',
    'uses' => 'UserController@refresh'
]);

$router->group(['prefix' => 'task', 'middleware' => 'auth:api'], function () use ($router) {
    $router->get('/', [
        'as' => 'task.all',
        'uses' => 'TaskController@all'
    ]);
});


