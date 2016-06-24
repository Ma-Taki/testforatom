<?php
/**
 * 汎用HTMLイブラリ
 *
 * HTML上で使用するタグの属性や、表示制御に関わる判定を返す
 *
 */
namespace App\Libraries;

use Carbon\Carbon;

class HtmlUtility
{
    const CHECKBOX_CHECKED = "checked";
    const NULL_CHARACTER = "";

    /**
     * テキストフィールド初期値設定用
     * 古いvalueが存在しなければ初期表示様Valueを返す
     * @param  string $value
     * @param  string $oldValue
     * @return string
     */
    public static function setTextValueByRequest($value, $oldValue){
        return $oldValue == null || $oldValue === "" ? $value : $oldValue;
    }

    /**
    * チェックボックをチェック済みで表示するか判定する
     * @param  array  $oldArray
     * @param  int  $intValue
     * @return string "checked" or ""
     */
    public static function isChecked($oldArray, $intValue){
        if($oldArray == null) return self::NULL_CHARACTER;
        $exist = in_array($intValue, $oldArray);
        return $exist ? self::CHECKBOX_CHECKED : self::NULL_CHARACTER;
    }

    /**
     * チェックボックをチェック済みで表示するか判定する
     * フラッシュデータに直前のチェックボックス配列が保存されていた場合、優先して参照する
     * フラッシュデータにチェックボックス配列が存在しない場合、初期表示用の配列を参照する
     * @param  array  $array
     * @param  array  $oldArray
     * @param  int  $intValue
     * @return string "checked" or ""
     */
    public static function isCheckedOldRequest($array, $oldArray, $intValue){
        $exist = false;
        if(!empty($oldArray)) {
            $exist = in_array($intValue, $oldArray);
        } elseif (empty($oldArray) && !empty($array) ) {
            $exist = in_array($intValue, $array);
        }

        return $exist ? self::CHECKBOX_CHECKED : self::NULL_CHARACTER;
    }

    /**
     * 今日日付が、指定された期間内かを判定する（日付単位)
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return bool
     */
     /*
    public static function isTodayInPeriod($fromDate, $toDate){
        // fromは当日 00:00:00から
        // toは明日 00:00:00まで
        if(Carbon::today()->between($fromDate, $toDate->addDays(1))){
            return true;
        }
        return false;
    }
    */

    /**
     * 今日日付が指定された期間前、期間中、期間終了かを判定し
     * 結果を文字で返す（日付単位)
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return String "前" or "中" or "後"
     */
    public static function isTodayInPeriod($fromDate, $toDate){
        $result = '';
        if(Carbon::today()->lt($fromDate)){
            $result = '前';
        } elseif(Carbon::today()->gt($toDate->addDays(1))){
            $result = '終了';
        } else {
            $result = '中';
        }
        return $result;
    }





}
