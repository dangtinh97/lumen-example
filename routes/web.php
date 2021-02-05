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
    return response()->json((new \App\Http\Responses\ResponseError(\App\Http\Responses\StatusCode::NOT_FOUND,'not found'))->toArray());
});

$router->post('login', 'UserController@login');
$router->post('user', 'UserController@create');

$router->group(['prefix'=>'/', 'middleware'=>'api'], function () use ($router){
    $router->group(['prefix'=>'comment'], function () use ($router){
        $router->get('', 'CommentController@index');
        $router->post('', 'CommentController@store');
        $router->put('/{id}', 'CommentController@update');
        $router->delete('/{id}', 'CommentController@destroy');
    });

    $router->group(['prefix'=>'diary'], function () use ($router){
        $router->get('/', 'DiaryController@index');
        $router->delete('/{id}', 'DiaryController@destroy');
        $router->delete('/', 'DiaryController@destroyAll');
    });

    $router->group(['prefix'=>'conversation'], function () use ($router){
        $router->get('/', 'ConversationController@index');
    });

    $router->group(['prefix'=>'message'], function () use ($router){
        $router->get('', 'MessageController@index');
        $router->post('/', 'MessageController@store');
        $router->delete('/{id}', 'MessageController@destroy');
        $router->delete('/delete_all/{id}', 'MessageController@destroyAll');
    });
});


