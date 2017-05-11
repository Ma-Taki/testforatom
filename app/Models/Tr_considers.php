<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_considers extends Model
{
    protected $table = "considers";
    protected $fillable = array('user_id','item_id','delete_flag');

}
