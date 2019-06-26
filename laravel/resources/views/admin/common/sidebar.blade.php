<?php
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
use Carbon\Carbon;


$menus = array(
    //案件管理
    array(
        'check_url' => array('item', 'category', 'position', 'system-type', 'skill-category', 'skill'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ITEM_READ),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon glyphicon-briefcase',
        'name'      => '案件管理',
        'span_flag' => 1,
        'sub_menus' => array(
            array(
                //案件一覧
                'check_url'     => array('item'),
                'class'         => '',
                'url'           => '/admin/item/search',
                'name'          => '案件一覧',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
            array(
                //案件登録
                'check_url'     => array('item'),
                'class'         => '',
                'url'           => '/admin/item/input',
                'name'          => '案件登録',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
            array(
                //タグ一覧
                'check_url'     => array('item'),
                'class'         => '',
                'url'           => '/admin/item/tags',
                'name'          => 'タグ一覧',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' =>null,
            ),
            array(
                //カテゴリー
                'check_url'     => array('category'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => 'カテゴリー',
                'i_class'       => 'glyphicon glyphicon-list-alt',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/category/search',
                        'name'  => 'カテゴリー一覧',
                    ),
                    array(
                        'url'   => '/admin/category/input?type=parent',
                        'name'  => '親カテゴリー登録',
                    ),
                    array(
                        'url'   => '/admin/category/input?type=child',
                        'name'  => '子カテゴリー登録',
                    ),
                    array(
                        'url'   => '/admin/category/list',
                        'name'  => 'トップページ表示管理',
                    ),
                ),
            ),
            array(
                //ポジション
                'check_url'     => array('position'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => 'ポジション',
                'i_class'       => 'glyphicon glyphicon-star',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/position/search',
                        'name'  => 'ポジション一覧',
                    ),
                    array(
                        'url'   => '/admin/position/input',
                        'name'  => 'ポジション登録',
                    ),
                ),
            ),
            array(
                //システム種別
                'check_url'     => array('system-type'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => 'システム種別',
                'i_class'       => 'glyphicon glyphicon-link',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/system-type/search',
                        'name'  => 'システム種別一覧',
                    ),
                    array(
                        'url'   => '/admin/system-type/input',
                        'name'  => 'システム種別登録',
                    ),
                ),
            ),
            array(
                //スキル
                'check_url'     => array('skill-category', 'skill'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => 'スキル',
                'i_class'       => 'glyphicon glyphicon-pencil',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/skill-category/search',
                        'name'  => 'スキルカテゴリー一覧',
                    ),
                    array(
                        'url'   => '/admin/skill-category/input',
                        'name'  => 'スキルカテゴリー登録',
                    ),
                    array(
                        'url'   => '/admin/skill/input',
                        'name'  => 'スキル登録',
                    ),
                ),
            ),

        )
    ),
    //会員管理
    array(
        'check_url' => array('member'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MEMBER_READ),
        'class'     => '', 
        'url'       => '/admin/member/search',
        'i_class'   => 'glyphicon glyphicon-user',
        'name'      => '会員管理',
        'span_flag' => 0,
        'sub_menus' => null,
    ),
    //エントリー管理
    array(
        'check_url' => array('entry'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_ENTRY_READ),
        'class'     => '', 
        'url'       => '/admin/entry/search',
        'i_class'   => 'glyphicon glyphicon-file',
        'name'      => 'エントリー管理',
        'span_flag' => 0,
        'sub_menus' => null,
    ),
    //メルマガ管理
    array(
        'check_url' => array('mail-magazine?type=new', 'mail-magazine'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MAIL_MAGAZINE),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon glyphicon-briefcase',
        'name'      => 'メルマガ管理',
        'span_flag' => 1,
        'sub_menus' => array(
                array(
                'check_url'     => array('mail-magazine?type=new'),
                'class'         => '',
                'url'           => '/admin/mail-magazine?type=new',
                'name'          => '新規作成',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
                array(
                'check_url'     => array('mail-magazine'),
                'class'         => '',
                'url'           => '/admin/mail-magazine/search',
                'name'          => '送信履歴',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
        )
    ),
    //ユーザ管理
    array(
        'check_url' => array('user'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon glyphicon-wrench',
        'name'      => 'ユーザ管理',
        'span_flag' => 1,
        'sub_menus' => array(
            array(
                'check_url'     => array('user'),
                'class'         => '',
                'url'           => '/admin/user/list',
                'name'          => 'ユーザ一覧',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
            array(
                'check_url'     => array('user'),
                'class'         => '',
                'url'           => '/admin/user/input',
                'name'          => 'ユーザ登録',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
        )
    ),
    //人気言語ランキング
    array(
        'check_url' => array('programming-lang-ranking'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER),
        'class'     => '', 
        'url'       => '/admin/programming-lang-ranking',
        'i_class'   => 'glyphicon glyphicon-ok',
        'name'      => '人気言語ランキング',
        'span_flag' => 0,
        'sub_menus' => null,
    ),
    //スライド画像管理
    array(
        'check_url' => array('slide'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_SLIDE_IMAGE),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon glyphicon-picture',
        'name'      => 'スライド画像管理',
        'span_flag' => 1,
        'sub_menus' => array(
            array(
                'check_url'     => array('slide'),
                'class'         => '',
                'url'           => '/admin/slide/list',
                'name'          => '画像一覧',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
            array(
                'check_url'     => array('slide'),
                'class'         => '',
                'url'           => '/admin/slide/input',
                'name'          => '画像登録',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
        )
    ),
    //特集記事紐付け
    array(
        'check_url' => array('column-connect'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_COLUMN_CONNECT),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon glyphicon-transfer',
        'name'      => '特集記事紐付け',
        'span_flag' => 1,
        'sub_menus' => array(
            array(
                'check_url'     => array('column-connect'),
                'class'         => '',
                'url'           => '/admin/column-connect/search',
                'name'          => '紐付け一覧',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
            array(
                'check_url'     => array('column-connect'),
                'class'         => '',
                'url'           => '/admin/column-connect/input',
                'name'          => '紐付け登録',
                'i_class'       => '',
                'span_flag'     => 0,
                'sub_sub_menus' => null,
            ),
        )
    ),
    //お知らせ管理
    array(
        'check_url' => array('admin-news', 'front-news'),
        'auth_item' => admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_NEWS),
        'class'     => 'submenu', 
        'url'       => '#',
        'i_class'   => 'glyphicon  glyphicon-bell',
        'name'      => 'お知らせ管理',
        'span_flag' => 1,
        'sub_menus' => array(
            array(
                //管理画面
                'check_url'     => array('admin-news'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => '管理画面',
                'i_class'       => 'glyphicon glyphicon-home',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/admin-news/search',
                        'name'  => 'お知らせ一覧',
                    ),
                    array(
                        'url'   => '/admin/admin-news/input',
                        'name'  => 'お知らせ登録',
                    ),
                ),
            ),
            array(
                //フロント画面
                'check_url'     => array('front-news'),
                'class'         => 'undermenu',
                'url'           => '#',
                'name'          => 'フロント画面',
                'i_class'       => 'glyphicon glyphicon-bullhorn',
                'span_flag'     => 1,
                'sub_sub_menus' => array(
                    array(
                        'url'   => '/admin/front-news/search',
                        'name'  => 'お知らせ一覧',
                    ),
                    array(
                        'url'   => '/admin/front-news/input',
                        'name'  => 'お知らせ登録',
                    ),
                ),
            ),
        )
    ),
);

//url
$nowUriArray = explode('/', $_SERVER['REQUEST_URI']);
$url = $nowUriArray[2];
?>

@if (!strstr(Request::url(), '/admin/login'))
    <div class="col-md-2">
        <div class="sidebar content-box" style="display: block;">
            <ul class="nav">
                <li class="current">
                    <a href="/admin/top">
                        <i class="glyphicon glyphicon-home"></i> トップ
                    </a>
                </li>
                @foreach($menus as $menu)
                    @if(admnUtil::isExistAuth(mdlUtil::AUTH_TYPE_MASTER) || $menu['auth_item'])
                        @if(in_array($url, $menu['check_url']))
                            <li class="{{ $menu['class'] }} open">
                        @else
                            <li class="{{ $menu['class'] }}">
                        @endif
                        <a href="{{ $menu['url'] }}">
                            <i class="{{ $menu['i_class'] }}"></i> {{ $menu['name'] }}
                            @if($menu['span_flag'])
                                <span class="caret pull-right"></span>
                            @endif
                        </a>
                        <ul>
                            @if(!empty($menu['sub_menus']))
                                @foreach($menu['sub_menus'] as $sub_menu)
                                    @if(in_array($url, $sub_menu['check_url']))
                                        <li class="{{ $sub_menu['class'] }} open">
                                    @else
                                        <li class="{{ $sub_menu['class'] }}">
                                    @endif
                                    <a href="{{ $sub_menu['url'] }}">
                                        @if(!empty($sub_menu['i_class']))
                                            <i class="{{ $sub_menu['i_class'] }}"></i>
                                        @endif
                                        {{ $sub_menu['name'] }}
                                        @if($sub_menu['span_flag'] == 1)
                                            <span class="caret pull-right"></span>
                                        @endif
                                    </a>
                                    @if(!empty($sub_menu['sub_sub_menus']))
                                        @if(in_array($url, $sub_menu['check_url']))
                                            <ul style="display: block;">
                                        @else
                                            <ul style="display: none;">
                                        @endif
                                        @foreach($sub_menu['sub_sub_menus'] as $sub_sub_menu)
                                            <li>
                                                <a href="{{ $sub_sub_menu['url'] }}">
                                                    {{ $sub_sub_menu['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                        </ul>
                                    @endif
                                        </li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif
