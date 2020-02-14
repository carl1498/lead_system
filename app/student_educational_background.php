<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_educational_background extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_educational_background';
    protected $fillable = [
        'stud_id', 'name', 'course', 'level', 'start', 'end'
    ];

    public $timestamps = true;
}
