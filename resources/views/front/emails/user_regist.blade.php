<html>
<head></head>
<body>
    <p>{{ $user_name.' ' }}様</p>
    <p>この度は、数あるサイトの中から弊社「Engineer-Route」（エンジニアルート）へ<br>ご登録頂きまして誠に有難うございます。</p>
    <p>一度お打ち合わせの機会を頂き、今までのご経験や今後のご希望、キャリアプランなどを<br>お聞き出ればと思っております。</p>
    <p>別途、弊社担当者からご連絡をさせて頂きますので、お忙しいところ恐れ入りますが、<br>その際はご対応頂けますと幸いです。</p>
    <p>
        ------------------------------------------------------------
    </p>
    <p>
        ログインに必要なメールアドレスは以下の通りとなります。<br>
        ◆メールアドレス: {{ $mail }}
    </p>
    <p>
        パスワードをお忘れの際は、以下のURLより再設定をお願い致します。<br>
        ◆{{ url('/user/reminder') }}
    </p>
    <p>
        ------------------------------------------------------------
    </p>
    <p>
※本メールは送信専用メールアドレスから配信されております。<br>
　そのため、本メールアドレスに返信頂きましても回答できません。
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
