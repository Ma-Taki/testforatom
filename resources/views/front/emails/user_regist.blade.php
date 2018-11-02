<html>
<head></head>
<body>
    <p>{{ $user_name.' ' }}様</p>
    <p>
        この度は、数あるサイトの中から弊社「Engineer-Route」（エンジニアルート）へ<br>
        ご登録頂きまして誠に有難うございます。
    </p>
    <p>
        一度お打ち合わせの機会を頂き、今までのご経験や今後のご希望、キャリアプランなどを<br>
        お聞きできればと思っております。
    </p>
    <p>
        別途、弊社担当者からお電話かメールにてご連絡をさせて頂きますので、お忙しいところ恐れ入りますが、<br>
        その際はご対応頂けますと幸いです。<br>
        <br>
    </p>
    @if(!$skillsheet_upload_flag)
        <p>
            業務経歴書(スキルシート)をご提出いただけましたら、案件ご紹介までのやりとりがスピーディーとなります。<br>
            お手数ではございますが、業務経歴書がアップロードされていなかった為ご提出いただけますと幸いです。<br>
            <br>
            ご提出方法は2通りございます。<br>
            ・ログイン後、マイページの「プロフィール変更」よりアップロード<br>
            ・entry@engineer-route.com宛にファイルを添付いただき送信<br>
            以上、どちらでも結構です。<br>
            <br>
            <br>
            今お持ちのスキルシートで問題ないか不安な方はアドバイスさせていただきますので、<br>
            ぜひお早めにご提出ください。<br>
            <br>
            また、弊社フォーマットである必要はございませんが、<br>
            スキルシートをお持ちでない方は下記URLからダウンロードいただけます。<br>
            <a href="https://goo.gl/4ci3Yp">https://goo.gl/4ci3Yp</a>
        </p>
    @endif
    <p>
        ------------------------------------------------------------
    </p>
    <p>
        ログインに必要なメールアドレスは以下の通りでございます。<br>
        ◆メールアドレス: {{ $mail }}
    </p>
    <p>
        ◆ログインURL： {{ url('/login') }}
    </p>
    <p>
        パスワードをお忘れの際は、以下のURLより再設定をお願い致します。<br>
        ◆{{ url('/user/reminder') }}
    </p>
    <p>
        ------------------------------------------------------------
    </p>
    <p>
        ※このメールはお申し込みいただいた際の情報を元に自動送信しております。<br>
        そのため、本メールアドレスに返信頂きましてもお答えできませんのでご了承ください。<br>
        <br>
        <br>
        それでは、{{ $user_name.' ' }}様とお会いできることを楽しみにしております。<br>
        今後ともどうぞ宜しくお願い致します。<br>
    </p>
    <p>
□■━━━━━━━━━━━━━━━━━━━━━━┓<br>
　　エンジニアルート運営事務局<br>
　　(運営会社：ソリッドシード株式会社)<br>
　　〒107-0062<br>
　　東京都港区南青山5-4-27 Barbizon104 3F<br>
　　TEL：(03)5774-5557　　 FAX：(03)5774-5559<br>
　　E-MAIL　：info@engineer-route.com<br>
　　URL：https://www.engineer-route.com/<br>
┗━━━━━━━━━━━━━━━━━━━━━━□■ 
    </p>
</body>
</html>
