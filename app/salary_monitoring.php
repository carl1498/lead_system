<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salary_monitoring extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'salary_monitoring';
    protected $fillable = [
        'emp_id', 'sal_type', 'rate', 'daily', 'period_from', 'period_to', 'pay_day',
    ];
    
    public function income(){
        return $this->hasOne('App\salary_income', 'sal_mon_id', 'id');
    }

    public function deduction(){
        return $this->hasOne('App\salary_deduction', 'sal_mon_id', 'id');
    }

    public function employee(){
        return $this->hasOne('App\employee', 'id', 'emp_id');
    }

    public function employee_many(){
        return $this->hasMany('App\employee', 'role_id', 'id');
    }

    public $timestamps = true;
}
