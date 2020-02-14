<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_emergency extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_emergency';
    protected $fillable = [
        'stud_id', 'fname', 'mname', 'lname', 'relationship', 'contact'
    ];

    public $timestamps = true;
}
