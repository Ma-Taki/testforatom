<?php
/**
 * フロント画面汎用ユーティリティー
 *
 */
namespace App\Libraries;
use App;
class FrontUtility
{
    const SEARCH_CONDITION_RATE = [
        0 => '指定しない',
        200000 => '20万円以上',
        300000 => '30万円以上',
        500000 => '50万円以上',
        800000 => '80万円以上',
        900000 => '90万円以上',
    ];

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

    // メール：会員登録完了
    const USER_REGIST_MAIL_TITLE = 'エンジニアルートにご登録頂きありがとうございます。';
    public $user_regist_mail_from = '';
    public $user_regist_mail_from_name = '';
    public $user_regist_mail_to = '';
    public $user_regist_mail_to_name = '';

    public function __construct(){
        switch (env('APP_ENV')) {
            case 'local':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(local)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_to_name = 'E-R開発者';

                break;
            case 'develop':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(develop)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';

                $this->user_regist_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_from_name = 'エンジニアルート';
                $this->user_regist_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_regist_mail_to_name = 'E-R開発者';
                break;
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
}
