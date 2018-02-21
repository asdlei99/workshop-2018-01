<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemMessageControl extends Model
{
    protected $table = 'system_message_control';

    public function scopeIds($query, $ids)
    {
        return $query->whereIn('message_id',$ids);
    }
}
