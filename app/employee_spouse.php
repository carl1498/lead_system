<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_spouse extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'employee_spouse';
    protected $fillable = [
        'emp_id', 'fname', 'mname', 'lname', 'birthdate', 'contact'
    ];

    public $timestamps = true;
}
