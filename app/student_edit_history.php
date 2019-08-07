<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_edit_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'student_edit_history';
    protected $fillable = [
        'stud_id',
        'field',
        'previous',
        'new',
        'edited_by'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id')->withTrashed();
    }

    public function edited_by_emp(){
        return $this->hasOne('App\employee', 'id', 'edited_by')->withTrashed();
    }

    public $timestamps = true;
}
