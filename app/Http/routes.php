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

//お知らせ画面
Route::get('/front-news/detail', 'front\NewsController@showDetail');

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

//検討中
Route::post('/considers/register', 'front\ConsiderController@ajaxRegister');//登録ボタン
Route::post('/considers/delete', 'front\ConsiderController@ajaxDelete');//削除ボタン
Route::get('/considers', 'front\ConsiderController@showConsiderItems');//一覧ページ

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
    // Route::get('/admin/top', function () {
    //     return view('admin.top');
    // });
    Route::get('/admin/top', 'admin\TopController@show');

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
    // 進捗状況選択
    Route::post('/admin/member/selectstatus', 'admin\MemberController@ajaxSelectMemberStatus');
    // 進捗状況メモ編集
    Route::post('/admin/member/editstatus', 'admin\MemberController@ajaxEditMemberStatus');
    // 進捗状況メモ編集
    Route::post('/admin/member/updatestatus', 'admin\MemberController@updateMemberStatus');

    // 案件管理
    // 新規登録画面
    Route::get('/admin/item/input', 'admin\ItemController@showItemInput');
    // 新規登録処理
    Route::post('/admin/item/input', 'admin\ItemController@insertItem');
    Route::post('/admin/item/input/suggesttags', 'admin\TagController@suggestTags');
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
    // タグ画面
    Route::get('/admin/item/tags', 'admin\TagController@showTags');
    Route::get('/admin/item/tags/delete', 'admin\TagController@deleteTags');
    Route::get('/admin/item/tags/search', 'admin\TagController@searchTags');

    // カテゴリー管理
    // 新規登録画面
    Route::get('/admin/category/input', 'admin\CategoryController@showCategoryInput');
    // 新規登録処理
    Route::post('/admin/category/input', 'admin\CategoryController@insertCategory');
    // コピーして新規登録画面(parent)
    Route::get('/admin/category/copy-parent-input', 'admin\CategoryController@showCopyParentInput');
    // コピーして新規登録処理(child)    
    Route::get('/admin/category/copy-child-input', 'admin\CategoryController@showCopyChildInput');
    Route::post('/admin/category/copy-child-input', 'admin\CategoryController@insertCopyCategory');
    // 詳細画面
    Route::get('/admin/category/detail', 'admin\CategoryController@showCategoryDetail');
    // 編集画面
    Route::get('/admin/category/modify', 'admin\CategoryController@showCategoryModify');
    // 更新処理
    Route::post('/admin/category/modify', 'admin\CategoryController@updateCategory');
    // 検索処理
    Route::get('/admin/category/search', 'admin\CategoryController@searchCategory');
    // 論理削除処理
    Route::get('/admin/category/delete', 'admin\CategoryController@deleteCategory');
    // 論理削除から復活処理
    Route::get('/admin/category/insert', 'admin\CategoryController@insertAgainCategory');
    // セレクトボックス切り替え
    Route::post('/admin/category/selectBox', 'admin\CategoryController@ajaxSelectBox');
    // セッション削除
    Route::post('/admin/category/session-forget', 'admin\CategoryController@ajaxSessionForget');
    // トップページ表示管理画面
    Route::get('/admin/category/list', 'admin\CategoryController@displayCategorylist');
    // トップページ表示更新処理
    Route::post('/admin/category/list', 'admin\CategoryController@displayUpdateCategory');

    // ポジション管理
    // 新規登録画面
    Route::get('/admin/position/input', 'admin\PositionController@showPositionInput');
    // 新規登録処理
    Route::post('/admin/position/input', 'admin\PositionController@insertPosition');
    // 編集画面
    Route::get('/admin/position/modify', 'admin\PositionController@showPositionModify');
    // 更新処理
    Route::post('/admin/position/modify', 'admin\PositionController@updatePosition');
    // 検索処理
    Route::get('/admin/position/search', 'admin\PositionController@searchPosition');
    // 論理削除処理
    Route::get('/admin/position/delete', 'admin\PositionController@deletePosition');
    // 論理削除から復活処理
    Route::get('/admin/position/insert', 'admin\PositionController@insertAgainPosition');

    // システム種別管理
    // 新規登録画面
    Route::get('/admin/system-type/input', 'admin\SystemTypeController@showSystemtypeInput');
    // 新規登録処理
    Route::post('/admin/system-type/input', 'admin\SystemTypeController@insertSystemtype');
    // 編集画面
    Route::get('/admin/system-type/modify', 'admin\SystemTypeController@showSystemtypeModify');
    // 更新処理
    Route::post('/admin/system-type/modify', 'admin\SystemTypeController@updateSystemtype');
    // 検索処理
    Route::get('/admin/system-type/search', 'admin\SystemTypeController@searchSystemtype');
    // 論理削除処理
    Route::get('/admin/system-type/delete', 'admin\SystemTypeController@deleteSystemtype');
    // 論理削除から復活処理
    Route::get('/admin/system-type/insert', 'admin\SystemTypeController@insertAgainSystemtype');

    // 要求スキル管理
    // 新規登録画面
    Route::get('/admin/skill-category/input', 'admin\SkillCategoryController@showSkillInput');
    // 新規登録処理
    Route::post('/admin/skill-category/input', 'admin\SkillCategoryController@insertSkill');
    // 編集画面
    Route::get('/admin/skill-category/modify', 'admin\SkillCategoryController@showSkillModify');
    // 更新処理
    Route::post('/admin/skill-category/modify', 'admin\SkillCategoryController@updateSkill');
    // 検索処理
    Route::get('/admin/skill-category/search', 'admin\SkillCategoryController@searchSkill');
    // 論理削除処理
    Route::get('/admin/skill-category/delete', 'admin\SkillCategoryController@deleteSkill');
    // 論理削除から復活処理
    Route::get('/admin/skill-category/insert', 'admin\SkillCategoryController@insertAgainSkill');
    // 新規登録画面
    Route::get('/admin/skill/input', 'admin\SkillController@showSkillInput');
    // 新規登録処理
    Route::post('/admin/skill/input', 'admin\SkillController@insertSkill');
    // 編集画面
    Route::get('/admin/skill/modify', 'admin\SkillController@showSkillModify');
    // 更新処理
    Route::post('/admin/skill/modify', 'admin\SkillController@updateSkill');
    // 検索処理
    Route::get('/admin/skill/search', 'admin\SkillController@searchSkill');
    // 論理削除処理
    Route::get('/admin/skill/delete', 'admin\SkillController@deleteSkill');
    // 論理削除から復活処理
    Route::get('/admin/skill/insert', 'admin\SkillController@insertAgainSkill');
    // セレクトボックス切り替え
    Route::post('/admin/skill/selectBox', 'admin\SkillController@ajaxSelectBox');

    // メルマガ管理
    Route::get('/admin/mail-magazine', 'admin\MailMagazineController@index');
    Route::post('/admin/mail-magazine', 'admin\MailMagazineController@store');
    Route::get('/admin/mail-magazine/search', 'admin\MailMagazineController@search');
    Route::get('/admin/mail-magazine/search/stop', 'admin\MailMagazineController@stopMailMagazine');
    Route::get('/admin/mail-magazine/search/start', 'admin\MailMagazineController@startMailMagazine');

    //人気プログラミング言語ランキング
    Route::get('/admin/programming-lang-ranking', 'admin\ProgrammingLangRankingController@index');
    Route::post('/admin/edit-programming-lang-ranking', 'admin\ProgrammingLangRankingController@editProgrammingLangRanking');
    Route::get('/admin/reset-programming-lang-ranking', 'admin\ProgrammingLangRankingController@getProgrammingLangRankingFromWebsite');

    //スライド画像管理画面
    // 一覧画面
    Route::get('/admin/slide/list', 'admin\SlideController@showSlideList');
    // 新規登録
    Route::resource('/admin/slide/input', 'admin\SlideController', ['only' => ['index', 'store']]);
    // 編集画面
    Route::get('/admin/slide/modify', 'admin\SlideController@showSlideModify');
    // 更新処理
    Route::post('/admin/slide/modify', 'admin\SlideController@updateAdminSlide');
    // 論理削除処理
    Route::get('/admin/slide/delete', 'admin\SlideController@deleteAdminSlide');
    // 論理削除から復活処理
    Route::get('/admin/slide/insert', 'admin\SlideController@insertAdminSlide');

    //特集記事紐付け管理画面
    // 一覧画面
    Route::get('/admin/column-connect/search', 'admin\ColumnConnectController@showColumnConnectList');
    //新規登録画面
    Route::get('/admin/column-connect/input', 'admin\ColumnConnectController@showColumnConnectInput');
    // 新規登録処理
    Route::post('/admin/column-connect/input', 'admin\ColumnConnectController@insertColumnConnect');
    // 編集画面
    Route::get('/admin/column-connect/modify', 'admin\ColumnConnectController@showColumnConnectModify');
    // 更新処理
    Route::post('/admin/column-connect/modify', 'admin\ColumnConnectController@updateColumnConnect');
    // 論理削除処理
    Route::get('/admin/column-connect/delete', 'admin\ColumnConnectController@deleteColumnConnect');
    // 論理削除から復活処理
    Route::get('/admin/column-connect/insert', 'admin\ColumnConnectController@insertAgainColumnConnect');

    //お知らせ管理(管理画面)
    // 詳細画面
    Route::get('/admin/admin-news/detail', 'admin\AdminNewsController@showDetail');
    // 一覧画面
    Route::get('/admin/admin-news/search', 'admin\AdminNewsController@showAdminNews');
    // 新規登録画面
    Route::get('/admin/admin-news/input', 'admin\AdminNewsController@showAdminNewsInput');
    // 新規登録処理
    Route::post('/admin/admin-news/input', 'admin\AdminNewsController@insertAdminNews');
    // 編集画面
    Route::get('/admin/admin-news/modify', 'admin\AdminNewsController@showAdminNewsModify');
    // 更新処理
    Route::post('/admin/admin-news/modify', 'admin\AdminNewsController@updateAdminNews');
    // 論理削除処理
    Route::get('/admin/admin-news/delete', 'admin\AdminNewsController@deleteAdminNews');
    // 論理削除から復活処理
    Route::get('/admin/admin-news/insert', 'admin\AdminNewsController@insertAgainAdminNews');

    //お知らせ管理(フロント画面)
    // 一覧画面
    Route::get('/admin/front-news/search', 'admin\FrontNewsController@showFrontNews');
    // 新規登録画面
    Route::get('/admin/front-news/input', 'admin\FrontNewsController@showFrontNewsInput');
    // 新規登録処理
    Route::post('/admin/front-news/input', 'admin\FrontNewsController@insertFrontNews');
    // 編集画面
    Route::get('/admin/front-news/modify', 'admin\FrontNewsController@showFrontNewsModify');
    // 更新処理
    Route::post('/admin/front-news/modify', 'admin\FrontNewsController@updateFrontNews');
    // 論理削除処理
    Route::get('/admin/front-news/delete', 'admin\FrontNewsController@deleteFrontNews');
    // 論理削除から復活処理
    Route::get('/admin/front-news/insert', 'admin\FrontNewsController@insertAgainFrontNews');
});
