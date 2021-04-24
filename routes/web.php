<?php

use App\Http\Controllers\PostController;

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
    return view('home', ['version' => $router->app->version()]);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    //Register User
    $router->post('/register', 'AuthController@register');
    //User Login
    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        //User Logout
        $router->post('/logout', 'AuthController@logout');
        //Get all posts
        $router->get('/posts', 'PostController@index');
        //Add a new post
        $router->post('/posts', 'PostController@store');
        //Update a post
        $router->put('/posts/{id}', 'PostController@update');
        //Delete a post
        $router->delete('/posts/{id}', 'PostController@destroy');
        //get a single post
        $router->get('/posts/{id}', 'PostController@single');
    });
});
