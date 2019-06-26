<?php
/**
 * フロント画面汎用ユーティリティー
 *
 */
namespace App\Libraries;
use App;
use App\Libraries\CookieUtility as CkieUtil;
use App\Libraries\ModelUtility as MdlUtil;
use App\Models\Tr_users;
use App\Models\Tr_items;
use App\Models\Tr_wp_posts;
use App\Models\Tr_wp_terms;
use App\Models\Tr_auth_keys;
use App\Models\Tr_programming_lang_ranking;
use App\Models\Ms_skills;
use Carbon\Carbon;
use DB;

class FrontUtility
{
    // スキルシートアップロードルール
    const FILE_UPLOAD_RULE = [
      'maximumSize' => 1024000,
      'allowedExtensions' => ['docx','xlsx','pptx','doc','xls','ppt','pdf',],
	    'allowedTypes' => [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'application/pdf',
      ],
    ];


    // 企業の皆様へ：お問い合わせ項目
    const COMPANY_CONTACT_TYPE = [
        '0' => '技術支援について',
        '1' => '受託開発について',
        '2' => 'Web制作について',
        '3' => '商品・サービスについて',
        '4' => '協業・ビジネスパートナーについて',
        '5' => '採用関連について',
        '6' => 'その他',
    ];

    // パスワード暗号化用salt
    const FIXED_SALT = "O#%1@'HfwJ2";

    // 認証キー発行時の有効時間(分):パスワード再設定
    const AUTH_KEY_LIMIT_MINUTE = 60;

    // 認証キー発行時の有効時間(時):メールアドレス認証
    const AUTH_KEY_LIMIT_HOUR = 24;

    // 条件から案件検索：報酬のラジオボタン
    const SEARCH_CONDITION_RATE = [
        0 => '指定しない',
        200000 => '20万円以上',
        300000 => '30万円以上',
        500000 => '50万円以上',
        800000 => '80万円以上',
        900000 => '90万円以上',
    ];

    // 案件一覧：ページ毎の表示数
    const SEARCH_PAGINATE = [
        '1' => 10,
        '2' => 20,
        '3' => 50,
    ];

    // トップページに表示する新着案件数
    const NEW_ITEM_MAX_RESULT = 4;

    // トップページに表示する急募案件数
    const PICK_UP_ITEM_MAX_RESULT = 4;

    // メール：お問い合わせ
    const USER_CONTACT_MAIL_TITLE = '【エンジニアルート】お問い合わせメール';
    public $user_contact_mail_from = '';
    public $user_contact_mail_to = '';

    // メール：企業向けお問い合わせ
    const COMPANY_CONTACT_MAIL_TITLE = '【エンジニアルート】企業向けお問い合わせメール';
    public $company_contact_mail_from = '';
    public $company_contact_mail_from_name = '';
    public $company_contact_mail_to = '';
    public $company_contact_mail_to_name = '';

    // メール：会員登録完了
    const USER_REGIST_MAIL_TITLE = '【Engineer-Route運営事務局】 ご登録ありがとうございます（自動送信メール）';
    public $user_regist_mail_from = '';
    public $user_regist_mail_from_name = '';
    public $user_regist_mail_to_bcc = '';

    // メール：エントリー完了
    const USER_ENTRY_MAIL_TITLE = '【Engineer-Route運営事務局】 案件へエントリーありがとうございます（自動送信メール）';
    public $user_entry_mail_from = '';
    public $user_entry_mail_from_name = '';
    public $user_entry_mail_to_bcc = '';

    // メール：パスワード再設定
    const USER_REMINDER_MAIL_TITLE = '【Engineer-Route運営事務局】 パスワード再設定URL通知メール';
    public $user_reminder_mail_from = '';
    public $user_reminder_mail_from_name = '';

    // メール：メールアドレス認証（メールアドレス変更）
    const MAIL_TITLE_CHANGE_MAIL_AUTH = '【Engineer-Route運営事務局】 メールアドレス変更URL通知メール';
    public $change_mail_auth_mail_from = '';
    public $change_mail_auth_mail_from_name = '';

    // メール：メールアドレス認証（新規会員登録）
    const MAIL_TITLE_REGIST_MAIL_AUTH = '【Engineer-Route運営事務局】 ご登録完了にお進みください（自動送信メール）';
    public $regist_mail_auth_mail_from = '';
    public $regist_mail_auth_mail_from_name = '';

    public function __construct(){
        switch (env('APP_ENV')) {
            // ローカル環境
            case 'local':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';

                $this->company_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_from_name = 'エンジニアルート';
                $this->company_contact_mail_to = 'y.suzuki@solidseed.co.jp';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to_bcc = '';

                $this->user_entry_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_entry_mail_from_name = 'エンジニアルート';
                $this->user_entry_mail_to_bcc = '';

                $this->user_reminder_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_reminder_mail_from_name = 'エンジニアルート';

                $this->regist_mail_auth_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->regist_mail_auth_mail_from_name = 'エンジニアルート';

                $this->change_mail_auth_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->change_mail_auth_mail_from_name = 'エンジニアルート';
                break;

            // 開発環境
            case 'develop':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';

                $this->company_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_from_name = 'エンジニアルート';
                $this->company_contact_mail_to = 'y.suzuki@solidseed.co.jp';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to_bcc = '';

                $this->user_entry_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_entry_mail_from_name = 'エンジニアルート';
                $this->user_entry_mail_to_bcc = '';

                $this->user_reminder_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_reminder_mail_from_name = 'エンジニアルート';

                $this->regist_mail_auth_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->regist_mail_auth_mail_from_name = 'エンジニアルート';

                $this->change_mail_auth_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->change_mail_auth_mail_from_name = 'エンジニアルート';

                break;

            //　本番環境
            case 'production':
                $this->user_contact_mail_from = 'sender@engineer-route.com';
                $this->user_contact_mail_to = 'info@engineer-route.com';

                $this->company_contact_mail_from = 'sender@engineer-route.com';
                $this->company_contact_mail_from_name = 'エンジニアルート';
                $this->company_contact_mail_to = 'info@engineer-route.com';

                $this->user_regist_mail_from = 'sender@engineer-route.com';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to_bcc = 'info@engineer-route.com';

                $this->user_entry_mail_from = 'sender@engineer-route.com';
                $this->user_entry_mail_from_name = 'エンジニアルート';
                $this->user_entry_mail_to_bcc = 'entry@engineer-route.com';

                $this->user_reminder_mail_from = 'sender@engineer-route.com';
                $this->user_reminder_mail_from_name = 'エンジニアルート';

                $this->regist_mail_auth_mail_from = 'sender@engineer-route.com';
                $this->regist_mail_auth_mail_from_name = 'エンジニアルート';

                $this->change_mail_auth_mail_from = 'sender@engineer-route.com';
                $this->change_mail_auth_mail_from_name = 'エンジニアルート';
                break;

            default:
                break;
        }
    }

    /**
     * ASCIIコードが32~126の範囲でランダムな文字列を作成する。
     * 引数は文字数。
     * @param int $len
     * @return String
     */
    public static function getPrefixSalt($len){
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $rand_int = mt_rand(32,126);
            $str .= chr($rand_int);
        }
        return $str;
    }

    /**
     * コレクション型のモデルリストを、idのみの配列に変換する
     * @param  Collection modelList
     * @return array
     */
    public static function convertCollectionToIdList($modelList) {
        $id_list = [];
        foreach ($modelList as $model) {
            array_push($id_list, $model->id);
        }
        return $id_list;
    }

    /**
     * ログインユーザを1件取得する
     *
     * @param int user_id
     * @return Tr_user / null
     */
    public static function getFirstLoginUser(){
        return Tr_users::getLoginUser()->first();
    }

    /**
     * ログイン中か判定する
     * @return bool
     */
    public static function isLogin(){
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get();
        return !$user->isEmpty();
    }

    /**
     * ログインユーザーの名前を取得する
     * @return string
     */
    public static function getLoginUserName(){
        $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
        $user = Tr_users::find($cookie);
        return $user->last_name .' ' .$user->first_name;
    }

    /**
     * UUIDを生成する(16進数40桁)
     * @return string $ticket
     */
    public static function createUUID(){
        $ticket = '';
        do {
            if (!empty($ticket)) Log::info('[duplicate UUID] ticket:'.$ticket);
            $ticket = sha1(uniqid(rand(), true));
            $w_obj = Tr_auth_keys::where('auth_task', $ticket)->get()->first();
        } while (!empty($w_obj));
        return $ticket;
    }

    /**
     * ソーシャルタイプの数値が正しいかチェックする
     * @param string $social_type
     * @return bool
     */
    public static function validateSocialType($social_type){
        return in_array($social_type, [
            strval(MdlUtil::SOCIAL_TYPE_TWITTER),
            strval(MdlUtil::SOCIAL_TYPE_FACEBOOK),
            strval(MdlUtil::SOCIAL_TYPE_GITHUB),
        ]);
    }

    //案件数が０の場合$lengthの件数だけランダムで案件を表示する
    public static function getItemsByRandom($itemList,$length){
      if($itemList->total() == 0){
        $today = Carbon::today();
        $randoms = DB::table('items')->inRandomOrder()->where('items.service_end_date', '<', $today)->limit($length)->get();
        return $randoms;
      }
      return null;
    }

    //コラムの最新記事をn件取得する
    public static function getRecentPosts($length){

      //記事を取得
      $itemList = Tr_wp_posts::select()
      ->where('post_type', 'post')
      ->where('post_status','publish')
      ->orderBy('post_date', 'desc')
      ->take($length)
      ->get();

      //各種設定
      foreach($itemList as $item){
        //アイキャッチ設定
        foreach($item->metas as $meta){
          if($meta->meta_key=="_thumbnail_id"){
            $eyecatch = Tr_wp_posts::select("guid")->where("ID",$meta->meta_value)->first();
            $item['thumb'] = $eyecatch->guid;
          }
        }
        //カテゴリー設定
        $cat_list = [];
        foreach($item->relationships as $relationship) {
            if ($relationship->taxonomy->taxonomy === 'category') {
                array_push($cat_list, $relationship->taxonomy->term->name);
            }
        }
        $item['category'] = $cat_list;
      }

      return $itemList;
    }


    /**
     * 人気プログラミング言語ランキング取得
     * @param int $length トップ10を取得したい場合は10
     */
    public static function getProgrammingLangRanking($length){
      //戻り値のランキング配列
      $top = [];
      //まずは今月のランキングを取得したい
      $dt = Carbon::now();
      $month = $dt->format('Ym');
      $month_for_display = $dt->format('Y年m月');
      //もし今月のランキングがなかったら
      if(!Tr_programming_lang_ranking::where('month', $month)->exists()){
        //先月のランキングを取得することにする
        $dt = Carbon::now()->subMonth();
        $month = $dt->format('Ym');
        $month_for_display = $dt->format('Y年m月');
      }
      //データベースから１０位までを取得
      $ranking = Tr_programming_lang_ranking::where('month',$month)->orderBy('ranking', 'asc')->take(10)->get();
      //テンプレートに渡すために配列に入れる
      foreach($ranking as $ranker){
        $top[] = $ranker['language'];
      }
      //ランキングと取得した月を返す
      return array( "ranking" => $top, "month" => $month_for_display);
    }



    /**
     * プログラミング言語別エンジニアルート案件数ランキング取得
     * @param int $length トップ10を取得したい場合は10
     */
    public static function getItemRankingByProgrammingLanguage($length){

      //戻り値のランキング配列
      $ranking = [];

      //登録されている全てのプログラミング言語を取得
      $langs = Ms_skills::where('skill_category_id',1) //言語のみを取得
                        ->where('id','!=',14) //HTMLは省く
                        ->get();

      //プログラミング言語別の案件数を取得
      foreach($langs as $lang){
        //各プログラミング言語の案件数を取得
        $count = Tr_items::select('items.*')
                          ->entryPossible()
                          ->getItemBySkills($lang['id'])
                          ->count();
        //各プログラミング言語の案件数と言語名を入れる
        $ranking[] = array("count" => $count, "lang" =>$lang['name'], "id" => $lang['id']);
      }

      //案件数が多い順にソート
      array_multisort(array_column($ranking, 'count'), SORT_DESC, $ranking);
      //10位より下は削除
      array_splice($ranking, $length);

      return $ranking;

    }



}
