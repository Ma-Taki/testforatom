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
        '指定しない' => '',
        '20万円以上' => 20,
        '30万円以上' => 30,
        '50万円以上' => 50,
        '80万円以上' => 80,
        '90万円以上' => 90,
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

    // お問い合わせ
    const USER_CONTACT_MAIL_TITLE = '【エンジニアルート】お問い合わせメール';
    public $user_contact_mail_from = '';
    public $user_contact_mail_from_name = '';
    public $user_contact_mail_to = '';
    public $user_contact_mail_to_name = '';

    public function __construct(){
        switch (env('APP_ENV')) {
            case 'local':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(local)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';
                break;
            case 'develop':
                $this->user_contact_mail_from = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_from_name = 'E-R開発(develop)';
                $this->user_contact_mail_to = 'y.suzuki@solidseed.co.jp';
                $this->user_contact_mail_to_name = 'E-R開発者';
                break;
            case 'master':
                $this->user_contact_mail_from = 'sender@engineer-route.com';
                $this->user_contact_mail_from_name = 'sender@engineer-route.com';
                $this->user_contact_mail_to = 'info@engineer-route.com';
                $this->user_contact_mail_to_name = 'info@engineer-route.com';
                break;
            default:
                break;
        }
    }
}
