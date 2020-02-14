<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_emp_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_emp_history';
    protected $fillable = [
        'stud_id', 'name', 'position', 'start', 'finished'
    ];

    public $timestamps = true;
}
