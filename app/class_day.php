<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class class_day extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'class_day';
    protected $fillable = [
        'class_settings_id', 'day_name_id', 'start_time_id', 'end_time_id'
    ];
}
