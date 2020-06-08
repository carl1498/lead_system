<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class student extends Model
{
    use SoftDeletes;
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
        'departure_month_id',
        'status',
        'coe_status'
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

    public function company(){
        return $this->hasOne('App\company', 'id', 'company_id');
    }

    public function referral(){
        return $this->hasOne('App\employee', 'id', 'referral_id')->withTrashed();
    }

    public function branch(){
        return $this->hasOne('App\branch', 'id', 'branch_id');
    }

    public function course(){
        return $this->hasOne('App\course', 'id', 'course_id');
    }

    public function university(){
        return $this->hasOne('App\university', 'id', 'university_id');
    }

    public function departure_year(){
        return $this->hasOne('App\departure_year', 'id', 'departure_year_id');
    }

    public function departure_month(){
        return $this->hasOne('App\departure_month', 'id', 'departure_month_id');
    }

    public function payment(){
        return $this->hasMany('App\tf_payment', 'stud_id', 'id')->where('tf_name_id', 3)->orderBy('date', 'asc');
    }

    public function emergency(){
        return $this->hasMany('App\student_emergency', 'stud_id', 'id');
    }

    public function employment(){
        return $this->hasMany('App\student_emp_history', 'stud_id', 'id');
    }

    public function education(){
        return $this->hasMany('App\student_educational_background', 'stud_id', 'id');
    }

    public $timestamps = true;
}
