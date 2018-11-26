<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
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

    public $timestamps = true;
}
