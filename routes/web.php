<?php

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

$app->get('/', ['uses' => 'IndexController@index', 'name' => 'index']);

$app->get('containers', ['uses' => 'User\ContainersController@index', 'as' => 'user.containers.index']);
$app->get('containers/{container}', ['uses' => 'User\ContainersController@show', 'as' => 'user.containers.show']);
$app->post('containers', ['uses' => 'User\ContainersController@store', 'as' => 'user.containers.store']);
$app->patch('containers/{container}', ['uses' => 'User\ContainersController@update', 'as' => 'user.containers.update']);
$app->delete('containers/{container}', ['uses' => 'User\ContainersController@destroy', 'as' => 'user.containers.destroy']);

$app->get('containers/{container}/media', ['uses' => 'User\ContainerMediaController@index', 'as' => 'user.containers.media.index']);
$app->get('containers/{container}/media/{media}', ['uses' => 'User\ContainerMediaController@show', 'as' => 'user.containers.media.show']);
$app->post('containers/{container}/media', ['uses' => 'User\ContainerMediaController@store', 'as' => 'user.containers.media.store']);
$app->patch('containers/{container}/media/{media}', ['uses' => 'User\ContainerMediaController@update', 'as' => 'user.containers.media.update']);
$app->delete('containers/{container}/media/{media}', ['uses' => 'User\ContainerMediaController@destroy', 'as' => 'user.containers.media.destroy']);

$app->get('account', 'AccountController@index');
