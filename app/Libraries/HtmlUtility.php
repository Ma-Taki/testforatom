<?php
/**
 * View用HTMLユーティリティー
 *
 * HTML上で使用するタグの属性や、表示制御に関わる判定を返す
 *
 */
namespace App\Libraries;

use Carbon\Carbon;

class HtmlUtility
{
    const CHECKBOX_CHECKED = "checked";
    const DROPDOWNLIST_SELECTED = "selected";
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
     * テキストフィールド初期値設定用
     * 古いvalueが存在しなければ初期表示様Valueを返す
     * @param  string $value
     * @param  string $oldValue
     * @return string
     */
    public static function setTextValueByRequestDefault($value, $oldValue, $defaultValue){
        if ($oldValue != null || $oldValue != '') {
            return $oldValue;
        } elseif ($value != null || $value != '') {
            return $value;
        }
        return $defaultValue;
    }

    /**
     * チェックボックをチェック済みで表示するか判定する
     * @param  array  $oldArray
     * @param  int  $intValue
     * @return string "checked" or ""
     */
    public static function isChecked($oldArray, $intValue){
        if(empty($oldArray)) return self::NULL_CHARACTER;
        $exist = in_array($intValue, (array)$oldArray);
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
     * 関連モデル配列を、idの配列に変換する
     * @param  Collection modelList
     * @return list
     */
    public static function convertModelListToIdList($modelList){
        $id_list = array();
        foreach ($modelList as $model) {
            array_push($id_list, $model->id);
        }
        return $id_list;
    }

    /**
     * タグモデル配列を、改行コードを含む文字列にして返却する
     * @param  Collection modelList
     * @return string
     */
    public static function convertTagModelToString($modelList){
        $tag_str = '';
        foreach ($modelList as $model) {
            $tag_str = $tag_str.$model->term."\n";
        }
        return $tag_str;
    }

    /**
     * スキルモデル配列を、スキル名を句読点で区切った文字列にして返却する
     * @param  Collection modelList
     * @return string
     */
    public static function convertSkillsMdlToNameStr($modelList){
        $skill_name_str = '';
        foreach ($modelList as $model) {
            $skill_name_str = $skill_name_str.$model->name.'、';
        }
        return rtrim($skill_name_str, '、');
    }

    /**
     * ドロップダウンリストの初期表示項目を判定する
     * フラッシュデータに直前の選択項目が保存されていた場合、優先して参照する
     * フラッシュデータに選択項目が存在しない場合、初期表示用の配列を参照する
     * @param  string  $element
     * @param  string  $oldElement
     * @param  string  $value
     * @return string "selected" or ""
     */
    public static function isSelectedOldRequest($element, $oldElement, $value){
        $exist = false;
        if(!empty($oldElement)) {
            if($value === $oldElement) $exist = true;
        } elseif (empty($oldElement) && !empty($element)) {
            if($value === $element) $exist = true;
        }

        return $exist ? self::DROPDOWNLIST_SELECTED : self::NULL_CHARACTER;
    }

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
    /**
     * 配列を受け取り、先頭から値が存在するかチェックする。
     * 値が存在した時点で返却する。
     *
     * @param
     * @return String
     */
    public static function getEnableFromBegin($param_array){
        foreach ((array)$param_array as $key => $value) {
            if(!empty($value))
            return $value;
        }
        return self::NULL_CHARACTER;
    }

    public static function getParamsString($params){
        $result = '';
        foreach ($params as $key => $value) {
            $result .= empty($result) ? '?':'&';
            $result .= $key ."=" .$value;
        }
        return $result;
    }

    public static function getDescriptionText ($item = null) {

        $description = '';

        if (!empty($item)) {
            if (is_array($item)) {
                //　案件一覧
            } else {
                // 案件詳細
                $description  = $item->area_detail.'エリア、'.$item->rate_detail.'/月。'. $item->name .'の案件・求人・仕事内容を掲載。';
                $description .= 'その他、この案件と類似したおすすめ案件も紹介しています。気に入った案件にはすぐにエントリー可能。';
                $description .= 'エンジニアルートでは、この他にもIT・WEB業界のフリーランスエンジニア・デザイナー向け案件を豊富に取り扱っております。';
                $description .= 'ぜひご利用ください。';
            }

        } else {

        }

        return $description;
    }

    /**
     * URLの存在を確認する
     * @param  String　$requestUri
     * @return boolean
     */
    public static function urlCheck($requestUri){
        $return = false;
        $pageContents = @file_get_contents((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $requestUri);
        if($pageContents !== false) {
          $return = true;
        }
        return $return;
    }

    /**
     * URL内のページタイトルを抽出する
     * @param  String $requestUri
     *  String
     */
    public static function urlTitleSubstr($requestUri){
        $pageTitle = @file_get_contents((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $requestUri);
        $pattern = array(
            '!<head>.*?>.*?</head.*?>!is',
            '!<header>.*?</header>!is',
            '!<script.*?>.*?</script.*?>!is',
            '!<style.*?>.*?</style.*?>!is',
            '!<nav.*?>.*?</nav.*?>!is',
            '!<div class="invisible-sp">.*?</div>!is',//PC用パンくず
            '!<div class="invisible-pc invisible-tab">.*?</div>!is',//スマホ用パンくず
            '!<p class="byline entry-meta vcard cf">.*?</p>!is',//公開・更新日時
            '!<div class="sns">.*?</div>!is',//SNS上ボタン
            '!<div class="sharewrap wow animated fadeIn" data-wow-delay="0.5s">.*?</div>!is',//SNS下ボタン
            '!<h2>.*?</h2>!is',//h2タグ
            '!<p>.*?</p>!is',//pタグ
            '!<div class="article-footer">.*?</div>!is',//タグ表示
            '!<div id="sidebar1" class="sidebar m-all t-all d-2of7 cf" role="complementary">.*?</div>!is',//最近の投稿
            '!<div id="categories-2" class="widget widget_categories"><h4 class="widgettitle">.*?</div>!is',//カテゴリー
            '!<footer class="bg">.*?</footer>!is'//footer
        );
        //strip_tagsに対応していないタグをpreg_replaceで先に除去する
        $pageTitle = preg_replace($pattern, '', $pageTitle);
        //titleタグのみ表示する
        $pageTitle = strip_tags($pageTitle,'<title>');
        echo $pageTitle;
    }

    /**
     * URL内のコンテンツ内容を抽出する
     * @param  String $requestUri
     *  String
     */
    public static function urlContentsSubstr($requestUri){
        $pageContents = @file_get_contents((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $requestUri);
        $pattern = array(
            '!<head>.*?>.*?</head.*?>!is',
            '!<header>.*?</header>!is',
            '!<script.*?>.*?</script.*?>!is',
            '!<style.*?>.*?</style.*?>!is',
            '!<nav.*?>.*?</nav.*?>!is',
            '!<div class="invisible-sp">.*?</div>!is',//PC用パンくず
            '!<div class="invisible-pc invisible-tab">.*?</div>!is',//スマホ用パンくず
            '!<p class="byline entry-meta vcard cf">.*?</p>!is',//公開・更新日時
            '!<div class="sns">.*?</div>!is',//SNS上ボタン
            '!<h1 class="entry-title page-title" itemprop="headline" rel="bookmark">.*?</h1>!is',//h1タグ
        );
        //strip_tagsに対応していないタグをpreg_replaceで先に除去する
        $pageContents = preg_replace($pattern, '', $pageContents);
        //h2,pタグのみ表示する
        $pageContents = strip_tags($pageContents,'<h2><p>');
        //400文字抽出
        $num = 400;
        if( mb_strlen($pageContents) > $num ) {
             $pageContents = mb_substr($pageContents,0,$num).'･･･';
        }
        echo $pageContents;
    }
}