<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// DB接続テスト用
Route::get('/test/db/conect', 'TestController@testDbConect');
// Jenkinsデプロイテスト用
Route::get('/test/jenkins/deploy', 'TestController@testJenkinsDeploy');
