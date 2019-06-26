<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_mail_magazines_send_to extends Model
{
    protected $table = 'mail_magazines_send_to';
    protected $fillable = ['mail_magazine_id', 'admin_id','mail_address'];
    public $timestamps = false;

}
