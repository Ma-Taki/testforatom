<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは正しいURLではありません。',
    'after'                => ':attributeは:date以降の日付にしてください。',
    'alpha'                => ':attributeは英字のみにしてください。',
    'alpha_dash'           => ':attributeは英数字とハイフンのみにしてください。',
    'alpha_num'            => ':attributeは英数字のみにしてください。',
    'array'                => ':attributeは配列にしてください。',
    'before'               => ':attributeは:date以前の日付にしてください。',
    'between'              => [
        'numeric' => ':attributeは:min〜:maxまでにしてください。',
        'file'    => ':attributeは:min〜:max KBまでのファイルにしてください。',
        'string'  => ':attributeは:min〜:max文字にしてください。',
        'array'   => ':attributeは:min〜:max個までにしてください。',
    ],
    'boolean'              => ':attributeはtrueかfalseにしてください。',
    'confirmed'            => ':attributeは確認用項目と一致していません。',
    'date'                 => ':attributeは正しい日付ではありません。',
    'date_format'          => ':attributeは":format"書式と一致していません。',
    'different'            => ':attributeは:otherと違うものにしてください。',
    'digits'               => ':attributeは:digits桁にしてください',
    'digits_between'       => ':attributeは:min〜:max桁にしてください。',
    'email'                => ':attributeを正しいメールアドレスにしてください。',
    'filled'               => ':attributeは必須です。',
    'exists'               => '選択された:attributeは正しくありません。',
    'image'                => ':attributeは画像にしてください。',
    'in'                   => '選択された:attributeは正しくありません。',
    'integer'              => ':attributeは整数にしてください。',
    'ip'                   => ':attributeを正しいIPアドレスにしてください。',
    'max'                  => [
        'numeric' => ':attributeは:max以下にしてください。',
        'file'    => ':attributeは:max KB以下のファイルにしてください。.',
        'string'  => ':attributeは:max文字以下にしてください。',
        'array'   => ':attributeは:max個以下にしてください。',
    ],
    'mimes'                => ':attributeは:valuesタイプのファイルにしてください。',
    'min'                  => [
        'numeric' => ':attributeは:min以上にしてください。',
        'file'    => ':attributeは:min KB以上のファイルにしてください。.',
        'string'  => ':attributeは:min文字以上にしてください。',
        'array'   => ':attributeは:min個以上にしてください。',
    ],
    'not_in'               => '選択された:attributeは正しくありません。',
    'numeric'              => ':attributeは数字にしてください。',
    'regex'                => ':attributeの書式が正しくありません。',
    'required'             => ':attributeは必須です。',
    'required_if'          => ':otherが:valueの時、:attributeは必須です。',
    'required_unless'      => '',
    'required_with'        => ':valuesが存在する時、:attributeは必須です。',
    'required_with_all'    => ':valuesが存在する時、:attributeは必須です。',
    'required_without'     => ':valuesが存在しない時、:attributeは必須です。',
    'required_without_all' => ':valuesが存在しない時、:attributeは必須です。',
    'same'                 => ':attributeと:otherは一致していません。',
    'size'                 => [
        'numeric' => ':attributeは:sizeにしてください。',
        'file'    => ':attributeは:size KBにしてください。.',
        'string'  => ':attribute:size文字にしてください。',
        'array'   => ':attributeは:size個にしてください。',
    ],
    'string'               => ':attributeは文字列にしてください。',
    'timezone'             => ':attributeは正しいタイムゾーンをしていしてください。',
    'unique'               => ':attributeは既に存在します。',
    'url'                  => ':attributeを正しい書式にしてください。',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        // 管理ユーザ情報更新
        'auths' => [
            'required_if' => '権限は一つ以上のチェックが必須です。'
        ],
        // お問い合わせ
        'user_name' => [
            'required' => '氏名が入力されておりません。'
        ],
        'email' => [
            'required'  => 'メールアドレスが入力されておりません。',
            'email'     => 'メールアドレスが正しくありません。',
            'confirmed' => '再入力したメールアドレスが一致しません。',
            'unique'    => '入力されたメールアドレスは既に登録されております。',
        ],
        'contactMessage' => [
            'required' => 'お問い合わせ内容が入力されておりません。'
        ],
        // 新規会員登録
        'last_name' => [
            'required' => '性が入力されておりません。'
        ],
        'first_name' => [
            'required' => '名が入力されておりません。'
        ],
        'last_name_kana' => [
            'required' => 'せいが入力されておりません。',
            'regex' => 'せいは全角平仮名で入力してください',
        ],
        'first_name_kana' => [
            'required' => 'めいが入力されておりません。',
            'regex' => 'めいは全角平仮名で入力してください',
        ],
        'gender' => [
            'required' => '性別が選択されておりません。'
        ],
        'birth' => [
            'date' => '指定された日付が不正です。'
        ],
        'mail' => [
            'required' => 'メールアドレスが入力されておりません'
        ],
        //後で統一
        'phone' => [
            'required' => '電話番号が入力されておりません。'
        ],
        'phone_num' => [
            'required'  => '電話番号が入力されておりません。',
            'regex'     => '電話番号が正しくありません。',
            'confirmed' => '再入力した電話番号が一致しません。',
        ],
        'password' => [
            'required' => 'パスワードが入力されておりません。',
            'between' => 'パスワードは:min〜:max文字で入力してください。',
            'confirmed' => '再入力したパスワードが一致しません。'
        ],
        'contact_type' => [
            'required' => 'お問い合わせ項目が選択されておりません。'
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        // ▽▽　管理ユーザ登録　▽▽
        'login_id' => 'ログインID',
        'password' => 'パスワード',
        'admin_name' => '管理者名',
        // △△　管理ユーザ登録　△△
        // ▽▽　案件登録　▽▽
        'item_name' => '案件名',
        'item_date_from' => 'エントリー受付期間(開始日)',
        'item_date_to' => 'エントリー受付期間(終了日)',
        'item_max_rate' => '報酬(検索用)',
        'item_rate_detail' => '報酬(表示用)',
        'areas' => 'エリア',
        'item_area_detail' => 'エリア詳細',
        'item_employment_period' => '就業期間',
        'item_working_hours' => '就業時間',
        'search_categories' => 'カテゴリ',
        'item_biz_category' => '業種',
        'job_types' => 'ポジション',
        'sys_types' => 'システム種別',
        'skills' => '要求スキル',
        'item_tag' => 'タグ',
        'item_detail' => '詳細',
        'item_note' => 'メモ(社内用)',
        // △△　案件登録　△△
        // ▽▽　お問い合わせ　▽▽
        'user_name' => '氏名',
        'mail' => 'メールアドレス',
        'contactMessage' => 'お問い合わせ内容',



    ],
];
