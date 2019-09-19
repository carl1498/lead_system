<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf_student extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tf_student';
    protected $fillable = [
        'stud_id', 'balance'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id');
    }

    public $timestamps = true;
}
