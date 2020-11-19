<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employee extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'employees';
    protected $fillable = [
        'fname', 
        'mname', 
        'lname', 
        'birthdate', 
        'contact_personal', 
        'contact_business', 
        'salary', 
        'picture',
        'address',
        'email',
        'branch_id',
        'gender',
        'employment_status',
        'role_id',
        'hired_date',
    ];

    public function branch(){
        return $this->hasOne('App\branch', 'id', 'branch_id');
    }

    public function role(){
        return $this->hasOne('App\role', 'id', 'role_id');
    }

    public function company_type(){
        return $this->hasOne('App\lead_company_type', 'id', 'lead_company_type_id');
    }

    public function benefits(){
        return $this->hasMany('App\employee_benefits', 'emp_id', 'id');
    }

    public function employment_history(){
        return $this->hasMany('App\employment_history', 'emp_id', 'id');
    }

    public function current_employment_status(){
        return $this->hasOne('App\employment_history', 'emp_id', 'id')->latest();
    }

    public function prev_employment_history(){
        return $this->hasMany('App\prev_employment_history', 'emp_id', 'id')->orderBy('hired_date', 'desc');
    }

    public function educational(){
        return $this->hasMany('App\educational_background', 'emp_id', 'id')->orderBy('end', 'desc');
    }

    public function employee_emergency(){
        return $this->hasMany('App\employee_emergency', 'emp_id', 'id');
    }

    public function emp_salary(){
        return $this->hasOne('App\emp_salary', 'emp_id', 'id');
    }

    public $timestamps = true;
}
