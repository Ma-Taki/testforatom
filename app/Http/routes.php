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

// フロント：トップ画面
Route::resource('/', 'FrontController', ['only' => ['index',]]);

// フロント：ご利用の流れ画面
Route::get('/front/flow', function () {
    return view('front.flowOfUse');
});

// フロント：Q&A画面
Route::get('/front/question', function () {
    return view('front.question');
});

// フロント：プライバシーポリシー画面
Route::get('/front/privacy', function () {
    return view('front.privacyPolicy');
});

// フロント：利用規約画面
Route::get('/front/terms', function () {
    return view('front.terms');
});

// フロント：お問い合わせ
Route::resource('/front/contact', 'front\ContactController', ['only' => ['index', 'store']]);
Route::post('/front/contact/confirm', 'front\ContactController@confirm');
Route::post('/front/contact/complete', 'front\ContactController@contact');

// フロント：エンジニアルートとは画面
Route::get('/front/about', function () {
    return view('front.about');
});

// フロント：企業の皆様へ画面
Route::get('/front/company', 'front\CompanyController@index');
Route::post('/front/company', 'front\CompanyController@store');

// フロント：案件一覧
Route::match(['get', 'post'], '/front/search', 'front\ItemController@searchItem');

// フロント(sp)：もっと見るボタン
Route::get('/front/ajax/readmore', 'front\ItemController@ajaxReadMore');
// フロント(sp)：条件から検索
Route::get('/front/sp/condition', function(){
    return view('front.sp.condition_search');
});
// フロント：キーワード検索
Route::get('/front/keyword/', 'front\ItemController@searchItemByKeyword');

// フロント：案件詳細
Route::get('/front/detail', 'front\ItemController@showItemDetail');
Route::get('/front/tag/{id}', 'front\ItemController@searchItemByTag');
Route::get('/front/category/{id}', 'front\ItemController@searchItemByCategory');
// フロント：急募案件
Route::get('/front/pickup', 'front\ItemController@searchItem');

// フロント：ログイン
Route::resource('/login', 'front\LoginController', ['only' => ['index', 'store']]);

// フロント：新規登録
Route::resource('/user/regist/input', 'front\UserController', ['only' => ['index', 'store']]);
//Route::post('/user/regist/confirm', 'front\UserController@confirm');
Route::post('/user/regist/complete', 'front\UserController@insertUser');


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
