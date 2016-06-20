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
Route::get('/admin/user/list', 'admin\UserController@showUserList')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：ユーザ管理：新規登録画面
Route::get('/admin/user/input', 'admin\UserController@showUserInput')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：ユーザ管理：ユーザ情報編集画面
Route::get('/admin/user/modify', 'admin\UserController@showUserModify')
->middleware(['loginCheck', 'authCheck']);


// 管理画面：ユーザ管理：新規登録処理
Route::post('/admin/user/insert', 'admin\UserController@insertAdminUser')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：ユーザ管理：更新処理
Route::post('/admin/user/update', 'admin\UserController@updateAdminUser')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：ユーザ管理：論理削除処理
Route::get('/admin/user/delete', 'admin\UserController@deleteAdminUser')
->middleware(['loginCheck', 'authCheck']);

// 管理画面：エントリー管理：一覧画面
Route::get('/admin/entry/list', 'admin\EntryController@showEntryList')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：エントリー管理：詳細画面
Route::get('/admin/entry/detail', 'admin\EntryController@showEntryDetail')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：エントリー管理：検索処理
Route::post('/admin/entry/search', 'admin\EntryController@searchEntry')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：エントリー管理：論理削除処理
Route::get('/admin/entry/delete', 'admin\EntryController@deleteEntry')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：エントリー管理：スキルシートダウンロード処理
Route::get('/admin/entry/download', 'admin\EntryController@downloadSkillSheet')
->middleware(['loginCheck', 'authCheck']);

// 管理画面：会員管理：一覧画面
Route::get('/admin/member/list', 'admin\MemberController@showMemberList')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：会員管理：詳細画面
Route::get('/admin/member/detail', 'admin\MemberController@showMemberDetail')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：会員管理：検索処理
Route::post('/admin/member/search', 'admin\MemberController@searchMember')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：会員管理：更新処理
Route::post('/admin/member/update', 'admin\MemberController@updatehMemberMemo')
->middleware(['loginCheck', 'authCheck']);
// 管理画面：会員管理：論理削除処理
Route::get('/admin/member/delete', 'admin\MemberController@deleteMember')
->middleware(['loginCheck', 'authCheck']);

// 管理画面：エラー画面
Route::get('/admin/error', function () {
    return view('admin.errors.error');
})->middleware(['loginCheck']);
