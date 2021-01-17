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


$router->group(['prefix'=>'example'],function () use ($router){
   $router->post('/','ExampleController@store');
});


