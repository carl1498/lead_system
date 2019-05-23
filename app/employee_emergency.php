<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_emergency extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'employee_emergency';
    protected $fillable = [
        'emp_id', 'fname', 'mname', 'lname', 'relationship', 'contact'
    ];

    public $timestamps;
}
