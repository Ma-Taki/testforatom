@extends('front.common.layout')
@section('title', '登録内容確認 - エンジニアルート')
@section('description', 'フリーランス、フリーエンジニアのためのIT系求人情報、案件情報満載。')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
?>
<div class="wrap">
    <div id="mypage" class="content">
        <h1 class="pageTitle">登録内容確認</h1>
        <hr class="partitionLine_02">
        <div class="mypage">

            <div class="mypage_element">
                <h2>アカウント</h2>
                <div class="mypage_elementInr">
                    <div class="dataTable">
                        <div class="data">
                            <div class="name">会員ID(メールアドレス)</div>
                            <div class="value">{{ $user->mail }}</div>
                        </div>
                        <div class="data">
                            <div class="name">パスワード</div>
                            <div class="value"><a href="/user/edit/password">パスワード変更</a></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mypage_element">
                <h2>プロフィール</h2>
                <div class="mypage_elementInr">
                    <div class="dataTable">
                        <div class="data">
                            <div class="name">氏名</div>
                            <div class="value">{{ $user->last_name .' ' .$user->first_name }}</div>
                        </div>
                        <div class="data">
                            <div class="name">氏名(かな)</div>
                            <div class="value">{{ $user->last_name_kana .' ' .$user->first_name_kana }}</div>
                        </div>
                        <div class="data">
                            <div class="name">性別</div>
                            <div class="value">{{ $user->sex == 'Male' ? '男性' : '女性' }}</div>
                        </div>
                        <div class="data">
                            <div class="name">生年月日</div>
                            <div class="value">{{ $user->birth_date->format('Y年n月j日') }}</div>
                        </div>
                        <div class="data">
                            <div class="name">最終学歴</div>
                            <div class="value">{{ $user->education_level }}</div>
                        </div>
                        <div class="data">
                            <div class="name">国籍</div>
                            <div class="value">{{ $user->nationality }}</div>
                        </div>
                        <div class="data">
                            <div class="name">希望の契約形態</div>
                            <div class="value">
                                @foreach($user->contractTypes as $conTyep)
                                    {{ $conTyep->name }}</br>
                                    @endforeach
                            </div>
                        </div>
                        <div class="data">
                            <div class="name">住所(都道府県)</div>
                            <div class="value">{{ $user->prefecture->name  }}</div>
                        </div>
                        <div class="data">
                            <div class="name">最寄り駅</div>
                            <div class="value">{{ $user->station }}</div>
                        </div>
                        <div class="data">
                            <div class="name">メールアドレス</div>
                            <div class="value">{{ $user->mail }}</div>
                        </div>
                        <div class="data">
                            <div class="name">電話番号</div>
                            <div class="value">{{ $user->tel }}</div>
                        </div>
                    </div>
                    <div class="commonCenterBtn">
                        <a href="/user/edit"><button>登録内容変更</button></a>
                    </div>
                </div>
            </div>

            <div class="commonCenterBtn user_delete_btn">
                <a href="/user/delete"><button>退会する</button></a>
            </div>
        </div>
    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
