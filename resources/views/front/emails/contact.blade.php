<html>
<head></head>
<body>
    <p>エンジニアルート-お問い合わせメール</p>
    <p style="margin: 0;">【問い合わせ日時】{{ $date }}</p>
    <p style="margin: 0;">【氏名】{{ $user_name }}</p>
    <p style="margin: 0;">【会社名】{{ $company_name }}</p>
    <p style="margin: 0;">【メールアドレス】{{ $mail }}</p>
    <p style="margin: 0;">【お問い合わせ内容】</p>
    <div style="white-space: pre-wrap;">{{ $contactMessage }}</div>
</body>
</html>
