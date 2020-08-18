<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class class_day extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'class_day';
    protected $fillable = [
        'class_settings_id', 'day_name_id', 'start_time', 'end_time'
    ];

    public function day_name(){
        return $this->hasOne('App\day_name', 'id', 'day_name_id');
    }

    public $timestamps = true;
}
