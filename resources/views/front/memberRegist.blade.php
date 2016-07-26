@extends('front.common.layout')
@section('content')
<div class="wrap">
    <div id="contact" class="content">
        <h1 class="pageTitle">お問い合わせ</h1>
        <hr class="partitionLine_02">
        <div class="contact">
            <p>以下のフォームに必要な情報を入力してください。※印の項目は入力必須です。</p>
            <hr class="partitionLine_01">

            <form method="post" action="{{ url('/front/contact') }}">

                <table class="inputTable">
                    <tr>
                        <td class="inputName" rowspan="2"><p>　お名前<span class="color-red">※</span></p></td>
                        <td class="inputField"><label>性　<input type="text" name="last_name" placeholder="ソリッド"></label></td>
                        <td class="inputField"><label>名　<input type="text" name="first_name" placeholder="太郎"></label></td>
                    </tr>
                    <tr>
                        <td class="inputField"><label>せい<input type="text" name="last_name" placeholder="そりっど"></label></td>
                        <td class="inputField"><label>めい<input type="text" name="first_name" placeholder="たろう"></label></td>
                    </tr>
                </table>
                <hr class="partitionLine_01">

            </form>
        </div>

    </div><!-- END CONTENT -->
</div><!-- END WRAP -->
@endsection
