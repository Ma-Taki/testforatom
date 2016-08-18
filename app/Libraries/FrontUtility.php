<?php
/**
 * フロント画面汎用ユーティリティー
 *
 */
namespace App\Libraries;
use App;
use App\Libraries\CookieUtility as CkieUtil;

class FrontUtility
{
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
    public $user_contact_mail_from_name = '';
    public $user_contact_mail_to = '';
    public $user_contact_mail_to_name = '';

    // メール：企業向けお問い合わせ
    const COMPANY_CONTACT_MAIL_TITLE = '【エンジニアルート】企業向けお問い合わせメール';
    public $company_contact_mail_from = '';
    public $company_contact_mail_from_name = '';
    public $company_contact_mail_to = '';
    public $company_contact_mail_to_name = '';

    // メール：会員登録完了
    const USER_REGIST_MAIL_TITLE = 'エンジニアルートにご登録頂きありがとうございます。';
    public $user_regist_mail_from = '';
    public $user_regist_mail_from_name = '';
    public $user_regist_mail_to = '';
    public $user_regist_mail_to_name = '';

    public function __construct(){
        switch (env('APP_ENV')) {
            // ローカル環境
            case 'local':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(local)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';

                $this->company_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_from_name = 'E-R開発(local)';
                $this->company_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_to_name = 'E-R開発者';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_to_name = 'E-R開発者';

                break;

            // 開発環境
            case 'develop':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(develop)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';

                $this->company_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_from_name = 'E-R開発(local)';
                $this->company_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->company_contact_mail_to_name = 'E-R開発者';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_to_name = 'E-R開発者';
                break;

            //　本番環境
            case 'master':
                $this->user_contact_mail_from = 'sender@engineer-route.com';
                $this->user_contact_mail_from_name = 'sender@engineer-route.com';
                $this->user_contact_mail_to = 'info@engineer-route.com';
                $this->user_contact_mail_to_name = 'info@engineer-route.com';

                $this->user_regist_mail_from = 'sender@engineer-route.com';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                break;
            default:
                break;
        }
    }

    /**
     * コレクション型のモデルリストを、idのみの配列に変換する
     * @param  Collection modelList
     * @return array
     */
    public static function convertCollectionToIdList($modelList) {
        $id_list = array();
        foreach ($modelList as $model) {
            array_push($id_list, $model->id);
        }
        return $id_list;
    }

    /**
     * ログイン中か判定する
     * @return bool
     */
    public static function isLogin(){
        return !is_null(\Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID));
    }
}
