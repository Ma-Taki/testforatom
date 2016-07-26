@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div id="contact" class="content">
        <h1 class="pageTitle">お問い合わせ</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p class="pageInfo">以下のフォームに必要な情報を入力してください。※印の項目は入力必須です。</p>
            <hr class="partitionLine_03">

            <form method="post" action="{{ url('/front/contact') }}">

                <table class="inputTable">
                    <tr>
                        <td class="inputName"><p>　氏名<span class="color-red">※</span></p></td>
                        <td class="inputField"><input type="text" name="name" placeholder="ソリッド　太郎"></td>
                    </tr>
                </table>
                <hr class="partitionLine_03">

                <table class="inputTable">
                    <tr>
                        <td class="inputName"><p>　会社名</p></td>
                        <td class="inputField"><input type="text" name="company_name" placeholder="ソリッドシード株式会社"></td>
                    </tr>
                </table>
                <hr class="partitionLine_03">

                <table class="inputTable">
                    <tr>
                        <td class="inputName">
                            <p>　メールアドレス<span class="color-red">※</span></p>
                        </td>
                        <td class="inputField">
                            <label><input type="text" name="mail" placeholder="info@solidseed.co.jp">（半角）</label>
                            <p class="mailConfirmText">確認のため、もう一度入力してください</p>
                            <label><input type="text" name="mail_confirmation" placeholder="info@solidseed.co.jp">（半角）</label>
                        </td>
                    </tr>
                </table>
                <hr class="partitionLine_03">

                <table class="inputTable">
                    <tr>
                        <td class="inputName"><p>　お問い合わせ内容<span class="color-red">※</span></p></td>
                        <td class="inputField">
                            <p>500文字以内で入力してください。</p>
                            <textarea name="message" cols="60" rows="10" maxlength="500"></textarea></td>
                    </tr>
                </table>
                <hr class="partitionLine_03">

                <div class="confirmBtn">
                    <button type="submit">入力内容の確認</button>
                </div>

                {{ csrf_field() }}
            </form>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
