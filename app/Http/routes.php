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

// 管理画面：ログイン画面
Route::get('/admin/login', function () {
    return view('admin.login');
});
// 管理画面：ログイン処理
Route::post('/admin/login', 'admin\LoginController@login');

// 管理画面；ログアウト
Route::get('/admin/logout', 'AdminController@logout');

// 管理画面：ログインチェックを行うルート
Route::group(['middleware' => 'loginCheck'], function () {
    // トップ画面
    Route::get('/admin/top', function () {
        return view('admin.top');
    });
    // エラー画面
    Route::get('/admin/error', function () {
        return view('admin.errors.error');
    });
});

// 管理画面：ログインチェックと権限チェックを行うルート
Route::group(['middleware' => ['loginCheck', 'authCheck']], function () {

    // ユーザ管理
    // 一覧画面
    Route::get('/admin/user/list', 'admin\UserController@showUserList');
    // 新規登録画面
    Route::get('/admin/user/input', 'admin\UserController@showUserInput');
    // 編集画面
    Route::get('/admin/user/modify', 'admin\UserController@showUserModify');
    // 新規登録処理
    Route::post('/admin/user/insert', 'admin\UserController@insertAdminUser');
    // 更新処理
    Route::post('/admin/user/update', 'admin\UserController@updateAdminUser');
    // 論理削除処理
    Route::get('/admin/user/delete', 'admin\UserController@deleteAdminUser');

    //エントリー管理
    // 詳細画面
    Route::get('/admin/entry/detail', 'admin\EntryController@showEntryDetail');
    // 検索処理
    Route::match(['get', 'post'], '/admin/entry/search', 'admin\EntryController@searchEntry');
    // 論理削除処理
    Route::get('/admin/entry/delete', 'admin\EntryController@deleteEntry');
    // スキルシートダウンロード処理
    Route::get('/admin/entry/download', 'admin\EntryController@downloadSkillSheet');

    // 会員管理
    // 詳細画面
    Route::get('/admin/member/detail', 'admin\MemberController@showMemberDetail');
    // 検索処理
    Route::match(['get', 'post'], '/admin/member/search', 'admin\MemberController@searchMember');
    // 更新処理
    Route::post('/admin/member/update', 'admin\MemberController@updatehMemberMemo');
    // 論理削除処理
    Route::get('/admin/member/delete', 'admin\MemberController@deleteMember');

    // 案件管理
    // 新規登録画面
    Route::get('/admin/item/input', 'admin\ItemController@showItemInput');
    // 詳細画面
    Route::get('/admin/item/detail', 'admin\ItemController@showItemDetail');
    // 編集画面
    Route::get('/admin/item/modify', 'admin\ItemController@showItemModify');
    // 検索処理
    Route::match(['get', 'post'], '/admin/item/search', 'admin\ItemController@searchItem');
    // 新規登録処理
    Route::post('/admin/item/insert', 'admin\ItemController@insertItem');
    // 更新処理
    Route::post('/admin/item/update', 'admin\ItemController@updateItem');
    // 論理削除処理
    Route::get('/admin/item/delete', 'admin\ItemController@deleteItem');
});
