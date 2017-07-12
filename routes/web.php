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

$app->get('containers', ['uses' => 'User\ContainerController@index', 'name' => 'user.containers.index']);
$app->get('containers/{container}', ['uses' => 'User\ContainerController@show', 'name' => 'user.containers.show']);
$app->post('containers', ['uses' => 'User\ContainerController@store', 'name' => 'user.containers.store']);
