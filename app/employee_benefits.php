<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_benefits extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'employee_benefits';
    protected $fillable = [
        'emp_id', 'benefits_id', 'id_number'
    ];

    public $timestamps = true;
}
