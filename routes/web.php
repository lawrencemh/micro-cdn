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

$app->get('containers', ['uses' => 'User\ContainerController@index', 'as' => 'user.containers.index']);
$app->get('containers/{container}', ['uses' => 'User\ContainerController@show', 'as' => 'user.containers.show']);
$app->post('containers', ['uses' => 'User\ContainerController@store', 'as' => 'user.containers.store']);
$app->patch('containers/{container}', ['uses' => 'User\ContainerController@update', 'as' => 'user.containers.update']);
$app->delete('containers/{container}', ['uses' => 'User\ContainerController@destroy', 'as' => 'user.containers.destroy']);

$app->get('containers/{container}/media', ['uses' => 'User\ContainerMediaController@index', 'as' => 'user.containers.media.index']);
$app->get('containers/{container}/media/{media}', ['uses' => 'User\ContainerMediaController@show', 'as' => 'user.containers.media.show']);