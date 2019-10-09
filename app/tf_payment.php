<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf_payment extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tf_payment';
    protected $fillable = [
        'stud_id', 'tf_name_id', 'amount', 'date', 'remarks'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id');
    }

    public function tf_name(){
        return $this->hasOne('App\tf_name', 'id', 'tf_name_id');
    }
}
