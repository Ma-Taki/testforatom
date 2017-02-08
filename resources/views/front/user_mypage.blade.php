@extends('front.common.layout')
@section('title', 'エンジニアルート | 登録内容確認')
@section('content')
<?php
    use App\Libraries\HtmlUtility as HtmlUtil;
    use App\Libraries\ModelUtility as MdlUtil;
    use App\Models\Tr_user_social_accounts;
?>
<div class="wrap">
    <div class="main-content user-mypage">
        <h1 class="main-content__title">登録内容確認</h1>
        <hr class="hr-2px-solid-5e8796">
        <div class="main-content__body">
            <div class="content__element">
                <div class="content__body">

                    @include('front.common.validation_error')
                    @include('front.common.custom_info_message')

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
                                    <div class="value hover-thin"><a href="/user/edit/password">パスワード変更</a></div>
                                </div>


                                <div class="data">
                                    <div class="name">Facebook連携</div>
                                    <div class="value">
@if(Tr_user_social_accounts::getFacebookAccount($user->id)->count() > 0)
                                        【Facebookアカウント認証済み】<a href="/auth/sns/cancel?social_type={{ MdlUtil::SOCIAL_TYPE_FACEBOOK }}" class="hover-thin">解除する</a>
@else
                                        <a href="/login/sns/facebook?func=auth" class="hover-thin">Facebookアカウントの認証</a>
@endif
                                    </div>
                                </div>

                                <div class="data">
                                    <div class="name">Twitter連携</div>
                                    <div class="value">
@if(Tr_user_social_accounts::getTwitterAccount($user->id)->count() > 0)
                                        【Twitterアカウント認証済み】<a href="/auth/sns/cancel?social_type={{ MdlUtil::SOCIAL_TYPE_TWITTER }}" class="hover-thin">解除する</a>
@else
                                        <a href="/login/sns/twitter?func=auth" class="hover-thin">Twitterアカウントの認証</a>
@endif
                                    </div>
                                </div>

                                <div class="data">
                                    <div class="name">GitHub連携</div>
                                    <div class="value">
@if(Tr_user_social_accounts::getGithubAccount($user->id)->count() > 0)
                                        【GitHubアカウント認証済み】<a href="/auth/sns/cancel?social_type={{ MdlUtil::SOCIAL_TYPE_GITHUB }}" class="hover-thin">解除する</a>
@else
                                        <a href="/login/sns/github?func=auth" class="hover-thin">GitHubアカウントの認証</a>
@endif
                                    </div>
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
                                    <div class="name">電話番号</div>
                                    <div class="value">{{ $user->tel }}</div>
                                </div>
                                <div class="data">
                                    <div class="name">メールアドレス</div>
                                    <div class="value">{{ $user->mail }}</div>
                                    <div class="value">
                                        <p>
                                            変更する場合は<a class="hover-thin" href="/user/edit/email">コチラ</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="data">
                                    <div class="name">メールマガジン</div>
                                    <div class="value">
                                        @if($user->magazine_flag) 配信を希望する @else 配信を希望しない @endif
                                    </div>
                                </div>

                            </div>
                            <div class="cmmn-btn">
                                <a href="/user/edit"><button class="edit-btn">登録内容変更</button></a>
                                <a href="/user/entry"><button class="edit-btn">エントリー履歴</button></a>
                            </div>
                        </div>
                    </div>

                    <div class="cmmn-btn">
                        <a href="/user/delete"><button>退会する</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection
