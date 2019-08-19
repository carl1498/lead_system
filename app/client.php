<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'client';
    protected $fillable = [
        'class_settings_id', 'stud_id', 'start_date', 'end_date'
    ];

    public function pic(){
        return $this->hasMany('App\client_pic', 'client_id', 'id');
    }

    public function bank(){
        return $this->hasOne('App\client_bank', 'client_id', 'id');
    }

    public function client_company_type(){
        return $this->hasOne('App\client_company_type', 'id', 'client_company_type_id');
    }

    public $timestamps = true;
}
