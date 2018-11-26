<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'students';
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'birthdate',
        'age',
        'contact',
        'program_id',
        'school_id',
        'benefactor_id',
        'address',
        'email',
        'referral_id',
        'date_of_signup',
        'date_of_medical',
        'date_of_completion',
        'gender',
        'branch_id',
        'course',
        'departure_year_id',
        'departure_month_id'
    ];

    public function program(){
        return $this->hasOne('App\program', 'id', 'program_id');
    }

    public function school(){
        return $this->hasOne('App\school', 'id', 'school_id');
    }

    public function benefactor(){
        return $this->hasOne('App\benefactor', 'id', 'benefactor_id');
    }

    public function referral(){
        return $this->hasOne('App\employee', 'id', 'referral_id');
    }

    public function branch(){
        return $this->hasOne('App\branch', 'id', 'branch_id');
    }

    public function departure_year(){
        return $this->hasOne('App\departure_year', 'id', 'departure_year_id');
    }

    public function departure_month(){
        return $this->hasOne('App\departure_month', 'id', 'departure_month_id');
    }

    public $timestamps = true;
}
