<?php

//スマホの場合に管理バーを表示させない
//表示崩れ回避のために使用
if(is_mobile()){
add_filter( 'show_admin_bar', '__return_false' );
}



/************* ORIGINAL CUSTOM FIELD *******************/

//投稿ページへ表示するカスタムボックスを定義する
add_action('admin_menu', 'add_cf_single_fullview');
//入力したデータの更新処理
add_action('save_post', 'save_custom_postdata');
 
//#################################
//投稿ページ用
//#################################
function add_cf_single_fullview() {
    add_meta_box( 'singlepostlayout','記事レイアウト', 'singlepostlayout_custom_field', 'post', 'side' );
}
 
//投稿ページに表示されるカスタムフィールド
function singlepostlayout_custom_field(){
       $id = get_the_ID();
       //カスタムフィールドの値を取得
        $singlepostlayout_radio = get_post_meta($id,'singlepostlayout_radio',true);
        $data = array(
             array("デフォルト（2カラム）","デフォルト（2カラム）",""),
	         array("フルサイズ（1カラム）","フルサイズ（1カラム）",""),
	         array("バイラル風（1カラム）","バイラル風（1カラム）",""),
         );
 
        foreach($data as $d){
        if($d[1]==$singlepostlayout_radio) $d[2] ="checked";
        echo <<<EOS
        <label style="display:block; padding:.3em 0;"><input type="radio" name="singlepostlayout_radio" value="{$d[1]}" {$d[2]}>{$d[0]}</label>
EOS;
    }
       echo <<<EOS
 
EOS;
}
/*データ更新・保存*/
function save_custom_postdata($post_id){
    $singlepostlayout_radio=isset($_POST['singlepostlayout_radio']) ? $_POST['singlepostlayout_radio']: null;
    $singlepostlayout_radio_ex = get_post_meta($post_id, 'singlepostlayout_radio', true);
    if($singlepostlayout_radio){
      update_post_meta($post_id, 'singlepostlayout_radio',$singlepostlayout_radio);
    }else{
      delete_post_meta($post_id, 'singlepostlayout_radio',$singlepostlayout_radio_ex);
    }
}