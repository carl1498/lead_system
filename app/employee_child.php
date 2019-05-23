<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_child extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'employee_child';
    protected $fillable = [
        'emp_id', 'fname', 'mname', 'lname', 'birthdate', 'gender'
    ];
    
    public $timestamps = true;
}
