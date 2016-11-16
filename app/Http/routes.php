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

// ▽▽▽ 公開画面ルート　▽▽▽

// トップ
Route::get('/', 'FrontController@showTop');

// 宣伝用
Route::get('/lp1', function() { return view('public.lp1'); });

// エンジニアルートとは
Route::get('/about', function () { return view('front.about'); });

// ご利用の流れ
Route::get('/flow', function () { return view('front.flowOfUse'); });

// Q&A
Route::get('/question', function () { return view('front.question'); });

// プライバシーポリシー
Route::get('/privacy', function () { return view('front.privacyPolicy'); });

// 利用規約画面
Route::get('/terms', function () { return view('front.terms'); });

// お問い合わせ
Route::resource('/contact', 'front\ContactController', ['only' => ['index', 'store']]);
Route::post('/contact/confirm', 'front\ContactController@confirm');
Route::post('/contact/complete', 'front\ContactController@complete');

// 企業の皆様へ
Route::resource('/company', 'front\CompanyController', ['only' => ['index', 'store']]);
Route::post('/company/confirm', 'front\CompanyController@confirm');
Route::post('/company/complete', 'front\CompanyController@complete');

// 案件検索
Route::match(['get', 'post'], '/item/search', 'front\ItemController@searchItem');
Route::get('/item/keyword/', 'front\ItemController@searchItemByKeyword');
Route::get('/item/tag/{id}', 'front\ItemController@searchItemByTag');
Route::get('/item/category/{id}', 'front\ItemController@searchItemByCategory');
Route::get('/item/detail', 'front\ItemController@showItemDetail');
// 案件検索(sp追加)
Route::get('/item/search/readmore', 'front\ItemController@ajaxReadMore');
Route::get('/item/search/condition', function() { return view('front.sp.condition_search'); });

// 新規会員登録
Route::get('/user/regist/auth', 'front\UserController@showMailAuth');
Route::post('/user/regist/auth', 'front\UserController@mailAuth');
Route::resource('/user/regist', 'front\UserController', ['only' => ['index', 'store']]);

// ログイン
Route::resource('/login', 'front\LoginController', ['only' => ['index', 'store']]);
Route::get('/logout', 'front\LoginController@logout');

// Twitter
Route::get('/login/sns/twitter',          'SNSController@getTwitterAuth');
Route::get('/login/sns/twitter/callback', 'SNSController@getTwitterAuthCallback');

// Facebook
Route::get('/login/sns/facebook', 'SNSController@getFacebookAuth');
Route::get('/login/sns/facebook/callback', 'SNSController@getFacebookAuthCallback');

// Github
Route::get('/login/sns/github', 'SNSController@getGithubAuth');
Route::get('/login/sns/github/callback', 'SNSController@getGithubAuthCallback');

// ログインチェックを行うルート
Route::group(['middleware' => 'front_loginCheck'], function () {
    // マイページ
    Route::get('/user', 'front\UserController@showMyPage');
    // パスワード変更
    Route::get('/user/edit/password', function(){ return view('front.edit_password'); });
    Route::post('/user/edit/password', 'front\UserController@updatePassword');
    // メールアドレス変更
    Route::get('/user/edit/email', 'front\UserController@showEmailEdit');
    Route::post('/user/edit/email', 'front\UserController@updateEmailAuth');
    Route::get('/user/edit/email/auth', 'front\UserController@updateEmail');
    // エントリー
    Route::resource('/entry', 'front\EntryController', ['only' => ['index', 'store']]);
    Route::get('/entry/download', 'front\EntryController@download');
    // 退会
    Route::get('/user/delete', function(){ return view('front.user_delete'); });
    Route::post('/user/delete', 'front\UserController@deleteUser');
    // プロフィール変更
    Route::get('/user/edit', 'front\UserController@showUserEdit');
    Route::post('/user/edit', 'front\UserController@updateUser');
    // SNS認証解除
    Route::get('/auth/sns/cancel', 'SNSController@deleteSNSAuth');
    // エントリー済み案件一覧
    Route::get('/user/entry', 'front\UserController@showEntryList');
    // エントリーシートのダウンロード
    Route::get('/user/entry/download', 'front\UserController@donwload');
});

// パスワード再設定URL送信
Route::get('/user/reminder', function(){ return view('front.user_reminder'); });
Route::post('/user/reminder', 'front\UserController@sendReminder');

// フロント：パスワード再設定
Route::get('/user/recovery', 'front\UserController@showRecovery');
Route::post('/user/recovery', 'front\UserController@recoverPassword');

// △△△ 公開画面ルート　△△△

// ▽▽▽ 管理画面ルート　▽▽▽

// ログイン
Route::get('/admin/login', function () {
    return view('admin.login');
});
Route::post('/admin/login', 'admin\LoginController@login');

// ログアウト
Route::get('/admin/logout', 'AdminController@logout');

// ログインチェックを行うルート
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

// ログインチェックと権限チェックを行うルート
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

    // メルマガ管理
    // メルマガ配信画面
    //Route::get('/admin/mail-magazine', 'admin\MailMagazineController@index');
});
Route::get('/admin/mail-magazine', 'admin\MailMagazineController@index');
Route::post('/admin/mail-magazine', 'admin\MailMagazineController@store');
