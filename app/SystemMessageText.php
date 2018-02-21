<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemMessageText extends Model
{
    protected $table = 'system_message_text';

    public function scopeUserGroup($query, $user_group = 3)
    {
        return $query->where('user_group',$user_group);
    }
}
