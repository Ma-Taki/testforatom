<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_mail_magazines extends Model
{
    protected $table = 'mail_magazines';

    public function mailaddresses()
    {
        return $this->hasMany('App\Models\Tr_mail_magazines_send_to','mail_magazine_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\Tr_link_users_mail_magazines','mail_magazine_id');
    }

}
