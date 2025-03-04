<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $guarded = [];

    public function users()
    {
    return $this->belongsToMany(User::class, 'user_calendar', 'calendar_id', 'user_id');
    }

}
