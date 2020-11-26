<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class class_settings extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    protected $table = 'class_settings';
    protected $fillable = [
        'sensei_id', 'start_date', 'end_date', 'remarks'
    ];

    public function sensei(){
        return $this->hasOne('App\employee', 'id', 'sensei_id')->withTrashed();
    }

    public function class_day(){
        return $this->hasMany('App\class_day', 'class_settings_id', 'id');
    }

    public $timestamps = true;
}
