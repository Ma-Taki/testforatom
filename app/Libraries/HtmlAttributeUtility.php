<?php
/**
 * HTML属性ライブラリ
 *
 * HTML上で使用するタグの属性関連の値を返却する
 *
 */
namespace App\Libraries;

class HtmlAttributeUtility
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
}
