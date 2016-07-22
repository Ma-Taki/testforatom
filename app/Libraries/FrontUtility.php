<?php
/**
 * フロント画面汎用ユーティリティー
 *
 */
namespace App\Libraries;

class FrontUtility
{
    const SEARCH_CONDITION_RATE = [
        '指定しない' => '',
        '20万円以上' => 20,
        '30万円以上' => 30,
        '50万円以上' => 50,
        '80万円以上' => 80,
        '90万円以上' => 90,
    ];

    // トップページに表示する新着案件数
    const NEW_ITEM_MAX_RESULT = 4;

    // トップページに表示する急募案件数
    const PICK_UP_ITEM_MAX_RESULT = 4;


}
