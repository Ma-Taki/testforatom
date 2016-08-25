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

// フロント：トップ
Route::resource('/', 'FrontController', ['only' => ['index',]]);
Route::get('/lp1', 'FrontController@index');

// フロント：エンジニアルートとは
Route::get('/about', function () {
    return view('front.about');
});

// フロント：ご利用の流れ
Route::get('/flow', function () {
    return view('front.flowOfUse');
});

// フロント：Q&A
Route::get('/question', function () {
    return view('front.question');
});

// フロント：プライバシーポリシー画面
Route::get('/privacy', function () {
    return view('front.privacyPolicy');
});

// フロント：利用規約画面
Route::get('/terms', function () {
    return view('front.terms');
});

// フロント：お問い合わせ
Route::resource('/contact', 'front\ContactController', ['only' => ['index', 'store']]);
Route::post('/contact/confirm', 'front\ContactController@confirm');
Route::post('/contact/complete', 'front\ContactController@complete');

// フロント：企業の皆様へ
Route::resource('/company', 'front\CompanyController', ['only' => ['index', 'store']]);
Route::post('/company/confirm', 'front\CompanyController@confirm');
Route::post('/company/complete', 'front\CompanyController@complete');

// フロント：案件一覧
Route::match(['get', 'post'], '/item/search', 'front\ItemController@searchItem');

// フロント(sp)：もっと見るボタン
Route::get('/item/search/readmore', 'front\ItemController@ajaxReadMore');
// フロント(sp)：条件から検索
Route::get('/item/search/condition', function(){
    return view('front.sp.condition_search');
});

// フロント：キーワード検索
Route::get('/item/keyword/', 'front\ItemController@searchItemByKeyword');

// フロント：特集タグ検索
Route::get('/item/tag/{id}', 'front\ItemController@searchItemByTag');

// フロント：カテゴリ検索
Route::get('/item/category/{id}', 'front\ItemController@searchItemByCategory');

// フロント：案件詳細
Route::get('/item/detail', 'front\ItemController@showItemDetail');

// フロント：ログイン
Route::resource('/login', 'front\LoginController', ['only' => ['index', 'store']]);
Route::get('/logout', 'front\LoginController@logout');

// フロント：マイページ
Route::get('/user', 'front\UserController@showMyPage');
// フロント：パスワード変更
Route::get('/user/edit/password', function(){
    return view('front.edit_password');
});
Route::post('/user/edit/password', 'front\UserController@updatePassword');
// フロント：新規会員登録
Route::resource('/user/regist', 'front\UserController', ['only' => ['index', 'store']]);

// フロント：エントリー
Route::resource('/entry', 'front\EntryController', ['only' => ['index', 'store']]);

// フロント：退会
Route::get('/user/delete', function(){
    return view('front.user_delete');
});
Route::post('/user/delete', 'front\UserController@deleteUser');

// フロント：退会
Route::get('/user/reminder', function(){
    return view('front.user_reminder');
});
Route::post('/user/reminder', 'front\UserController@sendReminder');

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
    // 新規登録処理
    Route::post('/admin/user/input', 'admin\UserController@insertAdminUser');
    // 編集画面
    Route::get('/admin/user/modify', 'admin\UserController@showUserModify');
    // 更新処理
    Route::post('/admin/user/modify', 'admin\UserController@updateAdminUser');
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
    // 新規登録処理
    Route::post('/admin/item/input', 'admin\ItemController@insertItem');
    // 詳細画面
    Route::get('/admin/item/detail', 'admin\ItemController@showItemDetail');
    // 編集画面
    Route::get('/admin/item/modify', 'admin\ItemController@showItemModify');
    // 更新処理
    Route::post('/admin/item/modify', 'admin\ItemController@updateItem');
    // 検索処理
    Route::match(['get', 'post'], '/admin/item/search', 'admin\ItemController@searchItem');
    // 論理削除処理
    Route::get('/admin/item/delete', 'admin\ItemController@deleteItem');
});
