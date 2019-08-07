<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_delete_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_delete_history';
    protected $fillable = [
        'stud_id',
        'deleted_by',
        'deleted_on'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id')->withTrashed();
    }
    
    public function deleted_by_emp(){
        return $this->hasOne('App\employee', 'id', 'deleted_by')->withTrashed();
    }

    public $timestamps = true;
}
