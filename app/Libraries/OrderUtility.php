<?php
/**
 * 並び替え処理ユーティリティ
 *
 */
namespace App\Libraries;

class OrderUtility
{
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

    // 管理画面：案件検索
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
}
