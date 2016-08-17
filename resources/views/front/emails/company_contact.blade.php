<html>
<head></head>
<body>
    <p>エンジニアルート-企業向けお問い合わせメール</p>
    <p style="margin: 0;">【問い合わせ日時】{{ $date }}</p>
    <p style="margin: 0;">【問い合わせ項目】{{ $contact_type_name }}</p>
    <p style="margin: 0;">【お名前】{{ $user_name }}</p>
    <p style="margin: 0;">【会社名】{{ $company_name }}</p>
    <p style="margin: 0;">【部署名】{{ $department_name }}</p>
    <p style="margin: 0;">【住所】{{ $address }}</p>
    <p style="margin: 0;">【電話番号】{{ $phone_num }}</p>
    <p style="margin: 0;">【メールアドレス】{{ $mail }}</p>
    <p style="margin: 0;">【URL】{{ $url }}</p>
    <p style="margin: 0;">【お問い合わせ内容】</p>
    <div style="white-space: pre-wrap;">{{ $contactMessage }}</div>
</body>
</html>
