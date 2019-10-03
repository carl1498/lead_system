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

    public function payment(){
        return $this->hasMany('App\tf_payment', 'tf_stud_id', 'id')->where('sign_up', 0);
    }

    public $timestamps = true;
}
