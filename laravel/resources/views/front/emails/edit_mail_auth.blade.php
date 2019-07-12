<html>
<head></head>
<body>
    <p>
        いつも「エンジニアルート」をご利用いただき、誠にありがとうございます。
    </p>
    <p>
        {{ $limit }}時間以内に下記のURLからメールアドレス変更を完了してください。<br>
        <a href={{ url('/user/edit/email/auth?id=' .$auth_key->user_id .'&ticket=' .$auth_key->ticket) }} ">
            {{ url('/user/edit/email/auth?id=' .$auth_key->user_id .'&ticket=' .$auth_key->ticket) }}
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
