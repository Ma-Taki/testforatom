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
use App\Models\Tr_auth_keys;
use Carbon\Carbon;
use DB;

class FrontUtility
{
    // スキルシートアップロードルール
    const FILE_UPLOAD_RULE = [
        'maximumSize' => 1024000,
		'allowedExtensions' => [
            'docx',
			'xlsx',
			'pptx',
			'doc',
			'xls',
            'ppt',
			'pdf',
        ],
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
    public $company_contact_mail_to = '';

    // メール：会員登録完了
    const USER_REGIST_MAIL_TITLE = 'エンジニアルートにご登録頂きありがとうございます。';
    public $user_regist_mail_from = '';
    public $user_regist_mail_from_name = '';
    public $user_regist_mail_to_bcc = '';

    // メール：エントリー完了
    const USER_ENTRY_MAIL_TITLE = '案件にエントリー頂きありがとうございます。';
    public $user_entry_mail_from = '';
    public $user_entry_mail_from_name = '';
    public $user_entry_mail_to_bcc = '';

    // メール：パスワード再設定
    const USER_REMINDER_MAIL_TITLE = 'パスワード再設定URL通知メール';
    public $user_reminder_mail_from = '';
    public $user_reminder_mail_from_name = '';

    // メール：メールアドレス認証（メールアドレス変更）
    const MAIL_TITLE_CHANGE_MAIL_AUTH = 'メールアドレス変更URL通知メール';
    public $change_mail_auth_mail_from = '';
    public $change_mail_auth_mail_from_name = '';

    // メール：メールアドレス認証（新規会員登録）
    const MAIL_TITLE_REGIST_MAIL_AUTH = '会員登録(無料)にお進みください。';
    public $regist_mail_auth_mail_from = '';
    public $regist_mail_auth_mail_from_name = '';

    public function __construct(){
        switch (env('APP_ENV')) {
            // ローカル環境
            case 'local':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';

                $this->company_contact_mail_from = 'y.suzuki@solidseed.co.jp';
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

}
