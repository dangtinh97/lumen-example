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

use App\Http\Middleware\Administrator;

$router->get('/', function () use ($router) {
    return response()->json((new \App\Http\Responses\ResponseError(\App\Http\Responses\StatusCode::NOT_FOUND, 'not found'))->toArray());
});


//$router->group(['prefix'=>'mode'],function () use ($router){
//
//    #USER
////    $router->Auth;
//   $router->post('/','ExampleController@store');
//   $router->post('user','UserController@create');
//   $router->patch('user','UserController@update');
//   $router->delete('user','UserController@delete');
//   $router->post('login','UserController@login');
//   $router->post('logout','UserController@logout');
//    $router->get('user','UserController@getUserInfo');
//
//   #POST
//    $router->post('post','PostController@create');
//    $router->patch('post/{id}','PostController@update');
//    $router->delete('post/{id}','PostController@delete');
//    $router->get('all_post','PostController@getAllPost');
//    $router->get('getPost_UserLogin','PostController@getPost_UserLogin');
//
//    #NOTIFICATION
//    $router->post('notification/{id_post}','NotifiController@create');
//    $router->get('notification','NotifiController@listNotification');
//    $router->delete('notification/{id_notification}','NotifiController@delete');
//});


$router->post('login', 'UserController@login');
$router->post('user', 'UserController@create');

$router->group(['prefix' => '', 'middleware' => 'api'], function () use ($router) {
    #USER
    $router->patch('user', 'UserController@update');
    $router->delete('user', 'UserController@delete');
    $router->post('logout', 'UserController@logout');
//    $router->post('logout', function (){
//        Auth::logout();
//        return (new ResponseSuccess('', 'Dang xuat thanh cong'));
//    });
    $router->get('user', 'UserController@getUserInfo');
    $router->get('user', 'UserController@getUserInfo');

    #POST
    $router->post('post', 'PostController@create');
    $router->patch('post/{id}', 'PostController@update');
    $router->delete('post/{id}', 'PostController@delete');
    $router->get('all_post', 'PostController@getAllPost');
    $router->get('getPost_UserLogin', 'PostController@getPost_UserLogin');

    #NOTIFICATION
    $router->post('notification/{id_post}', 'NotifiController@create');
    $router->get('notification', 'NotifiController@listNotification');
    $router->delete('notification/{id_notification}', 'NotifiController@delete');
});


