<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_link_users_mail_magazines extends Model
{
    protected $table = 'link_users_mail_magazines';
    protected $fillable = ['mail_magazine_id', 'admin_id','user_id'];
    protected $primaryKey  = 'mail_magazine_id';
    public $timestamps = false;

}
