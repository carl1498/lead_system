<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class class_students extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'class_students';
    protected $fillable = [
        'class_settings_id', 'stud_id', 'start_date', 'end_date'
    ];

    public function current_class(){
        return $this->hasOne('App\class_settings', 'id', 'class_settings_id');
    }

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id')->withTrashed();
    }

    public $timestamps = true;
}
