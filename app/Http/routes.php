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

// 管理画面：ログイン
Route::get('/admin/login', 'admin\LoginController@index');
Route::post('/admin/login', 'admin\LoginController@store');

// 管理画面；ログアウト
Route::get('/admin/logout', 'AdminController@logout');

// 管理画面：TOP
Route::get('/admin/top', function () {
    return view('admin.top');
})->middleware(['loginCheck']);

// 管理画面：ユーザ管理：一覧画面
Route::get('/admin/user/list', 'admin\UserController@showUserList')->middleware(['loginCheck', 'authCheck']);
// 管理画面：ユーザ管理：新規登録画面
Route::get('/admin/user/input', 'admin\UserController@showUserInput')->middleware(['loginCheck']);
// 管理画面：ユーザ管理：ユーザ情報編集画面
Route::get('/admin/user/modify', 'admin\UserController@showUserModify')->middleware(['loginCheck']);


// 管理画面：ユーザ管理：新規登録処理
Route::post('/admin/user/insert', 'admin\UserController@insertAdminUser')->middleware(['loginCheck']);
// 管理画面：ユーザ管理：更新処理
Route::post('/admin/user/update', 'admin\UserController@updateAdminUser')->middleware(['loginCheck']);
// 管理画面：ユーザ管理：論理削除処理
Route::get('/admin/user/delete', 'admin\UserController@deleteAdminUser')->middleware(['loginCheck']);

// 管理画面：エラー画面
Route::get('/admin/error', function () {
    return view('admin.errors.error');
})->middleware(['loginCheck']);


// DB接続テスト用
Route::get('/test/db/conect', 'TestController@testDbConect');
// Jenkinsデプロイテスト用
Route::get('/test/jenkins/deploy', 'TestController@testJenkinsDeploy');
