<html>
<head></head>
<body>
    <p>{{ $user_name }}様</p>
    <p>
        この度は、「エンジニアルート」に掲載している案件へご応募頂きまして有難うございます。
    </p>
    <p>
        追ってエンジニアルートを運営しておりますソリッドシードの担当者から<br>
       ご連絡させて頂きますので、お待ち下さい。
    </p>
    <p>
       一週間経ってもご連絡がない場合は、お手数ではございますが、<br>
        {{ $admin_mail_addresses }}までご連絡下さい。
    </p>
    <p>
        ------------------------------------------------------------
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
        &nbsp;*　{{ url('/') }}<br>
        &nbsp;*/
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
