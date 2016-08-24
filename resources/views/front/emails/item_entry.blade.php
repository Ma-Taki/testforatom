<html>
<head></head>
<body>
    <p>{{ $user_name }}様</p>
    <p>
        この度は、数あるサイトの中から弊社「エンジニアルート」へ<br>
        エントリー頂きまして誠に有難うございます。
    </p>
    <p>
        追ってエンジニアルートを運営しておりますソリッドシード(株)の担当者から
        ご連絡させて頂きますので、少々お待ちください。
    </p>
    <p>
        一週間経ってもご連絡がない場合は、お手数ではございますが、<br>
        {{ $admin_mail_addresses }}までご連絡下さい。
    </p>
    <p>
        【エントリーID】<br>
        &nbsp;{{ $entry_id }}<br>
        【案件ID】<br>
        &nbsp;{{ $item_id }}<br>
        【案件名】<br>
        &nbsp;{{ $item_name }}<br>
@if(!empty($item_biz_category))
        【業種】<br>
        &nbsp;{{ $item_biz_category }}<br>
@endif
        【案件情報】<br>
        &nbsp;{{ url('/item/detail?id=' .$item_id) }}
    </p>
    <p>
        /*<br>
        &nbsp;*　エンジニアルート<br>
        &nbsp;*　http://www.engineer-route.com/<br>
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
