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

// $app->get('/', function () use ($app) {
    // return $app->welcome();
// });

// $app->get('/', function () {
    // return view('index');
// });

$app->get('/', 'MainController@getIndex');
$app->get('absensi', 'MainController@getAbsensi');
$app->get('pendapatan', 'MainController@getPendapatan');
$app->get('ajax-absensi/{id:[0-9]+}/{bulan:[0-9]+}', 'MainController@getAjaxAbsensi');
$app->get('ajax-pendapatan/{id:[0-9]+}/{bulan:[0-9]+}', 'MainController@getAjaxPendapatan');

