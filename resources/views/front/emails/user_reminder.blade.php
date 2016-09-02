<html>
<head></head>
<body>
    <p>
        いつも「エンジニアルート」をご利用いただき、誠にありがとうございます。
    </p>
    <p>
        {{ $limit }}分以内に下記のURLからパスワードを再設定してください。<br>
        <a href={{ url('/user/recovery?id=' .$auth_key->id .'&amp;ticket=' .$auth_key->ticket) }} ">
            {{ url('/user/recovery?id=' .$auth_key->id .'&amp;ticket=' .$auth_key->ticket) }}
        </a>
    </p>
    <p>
        /*<br>
        &nbsp;*　エンジニアルート<br>
        &nbsp;*　{{ url('/') }}<br>
        &nbsp;*/
    </p>
    <p>
        ------------------------------------------------------------
    </p>
    <p>
        本メールは送信専用メールアドレスから配信されております。<br>
        そのため、本メールアドレスに返信頂きましても回答できません。
    </p>
</body>
</html>
