<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class educational_background extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'educational_background';
    protected $fillable = [
        'emp_id', 'school', 'start', 'end', 'course_id', 'level', 'awards'
    ];

    public $timestamps = true;

    public function employee(){
        return $this->hasOne('App\employee', 'id', 'emp_id');
    }
    
    public function course(){
        return $this->hasOne('App\course', 'id', 'course_id');
    }
}
