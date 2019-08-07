<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_add_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_add_history';
    protected $fillable = [
        'stud_id',
        'type',
        'added_by'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id')->withTrashed();
    }

    public function added_by_emp(){
        return $this->hasOne('App\employee', 'id', 'added_by')->withTrashed();
    }

    public $timestamps = true;
}
