<?php
/**
 * 並び替え処理ユーティリティ
 *
 */
namespace App\Libraries;

class OrderUtility
{
    // ▽▽▽ 案件表示順 ▽▽▽
    const ORDER_ITEM_SERVICE_DESC = [
        'sortId' => 'ServiceDesc',
        'sortName' => '受付開始日順',
        'columnName' => 'service_start_date',
        'sort' => 'desc'
    ];
    const ORDER_ITEM_SERVICE_ASC = [
        'sortId' => 'ServiceAsc',
        'sortName' => '受付終了日順',
        'columnName' => 'service_end_date',
        'sort' => 'asc'
    ];
    const ORDER_ITEM_REGISTRATION_DESC = [
        'sortId' => 'RegistrationDesc',
        'sortName' => '追加順',
        'columnName' => 'registration_date',
        'sort' => 'desc'
    ];
    const ORDER_ITEM_RATE_DESC = [
        'sortId' => 'RateDesc',
        'sortName' => '報酬が高い順',
        'columnName' => 'max_rate',
        'sort' => 'desc'
    ];
    const ORDER_ITEM_RATE_ASC = [
        'sortId' => 'RateAsc',
        'sortName' => '報酬が安い順',
        'columnName' => 'max_rate',
        'sort' => 'asc'
    ];
    const ORDER_ITEM_AREA_ASC = [
        'sortId' => 'AreaAsc',
        'sortName' => 'エリア順',
        'columnName' => 'area_detail',
        'sort' => 'asc'
    ];
    const ORDER_ITEM_UPDATE_DESC = [
        'sortId' => 'UpdateDesc',
        'sortName' => '更新日が新しい順',
        'columnName' => 'last_update',
        'sort' => 'desc'
    ];
    const ORDER_ITEM_UPDATE_ASC = [
        'sortId' => 'UpdateAsc',
        'sortName' => '更新日が古い順',
        'columnName' => 'last_update',
        'sort' => 'asc'
    ];

    const ItemOrder = [
        'ServiceDesc' => self::ORDER_ITEM_SERVICE_DESC,
        'ServiceAsc' => self::ORDER_ITEM_SERVICE_ASC,
        'RegistrationDesc' => self::ORDER_ITEM_REGISTRATION_DESC,
        'RateDesc' => self::ORDER_ITEM_RATE_DESC,
        'RateAsc' => self::ORDER_ITEM_RATE_ASC,
        'AreaAsc' => self::ORDER_ITEM_AREA_ASC,
        'UpdateDesc' => self::ORDER_ITEM_UPDATE_DESC,
        'UpdateAsc' => self::ORDER_ITEM_UPDATE_ASC,
    ];
    // △△△ 案件表示順 △△△

    // ▽▽▽ エントリー表示順 ▽▽▽
    const ORDER_ENTRY_DATE_DESC = [
        'sortId' => 'EntryDateDesc',
        'sortName' => 'エントリー日付が新しい順',
        'columnName' => 'entry_date',
        'sort' => 'desc'
    ];
    const ORDER_ENTRY_DATE_ASC = [
        'sortId' => 'EntryDateAsc',
        'sortName' => 'エントリー日付が古い順',
        'columnName' => 'entry_date',
        'sort' => 'asc'
    ];

    const EntryOrder = [
        'EntryDateDesc' => self::ORDER_ENTRY_DATE_DESC,
        'EntryDateAsc' => self::ORDER_ENTRY_DATE_ASC,
    ];
    // △△△ エントリー表示順 △△△

    // ▽▽▽ 会員表示順 ▽▽▽
    const ORDER_MEMBER_REGISTRATION_DESC = [
        'sortId' => 'RegistrationDesc',
        'sortName' => '登録日が新しい順',
        'columnName' => 'registration_date',
        'sort' => 'desc'
    ];
    const ORDER_MEMBER_REGISTRATION_ASC = [
        'sortId' => 'RegistrationAsc',
        'sortName' => '登録日が古い順',
        'columnName' => 'registration_date',
        'sort' => 'asc'
    ];
    const ORDER_MEMBER_NAME_ASC = [
        'sortId' => 'LastNameKanaAsc',
        'sortName' => '氏名昇順',
        'columnName' => 'last_name_kana',
        'sort' => 'asc'
    ];
    const ORDER_MEMBER_NAME_DESC = [
        'sortId' => 'LastNameKanaDesc',
        'sortName' => '氏名降順',
        'columnName' => 'last_name_kana',
        'sort' => 'desc'
    ];
    const ORDER_MEMBER_MAIL_ASC = [
        'sortId' => 'MailAsc',
        'sortName' => '会員ID(メールアドレス)昇順',
        'columnName' => 'mail',
        'sort' => 'asc'
    ];
    const ORDER_MEMBER_MAIL_DESC = [
        'sortId' => 'MailDesc',
        'sortName' => '会員ID(メールアドレス)降順',
        'columnName' => 'mail',
        'sort' => 'desc'
    ];

    const MemberOrder = [
        'RegistrationDesc' => self::ORDER_MEMBER_REGISTRATION_DESC,
        'RegistrationAsc' => self::ORDER_MEMBER_REGISTRATION_ASC,
        'LastNameKanaAsc' => self::ORDER_MEMBER_NAME_ASC,
        'LastNameKanaDesc' => self::ORDER_MEMBER_NAME_DESC,
        'MailAsc' => self::ORDER_MEMBER_MAIL_ASC,
        'MailAsc' => self::ORDER_MEMBER_MAIL_DESC,
    ];
    // △△△ 会員表示順 △△△
}
