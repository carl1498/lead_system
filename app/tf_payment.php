<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf_payment extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tf_payment';
    protected $fillable = [
        'tf_stud_id', 'amount', 'date', 'remarks'
    ];

    public function student(){
        return $this->hasOne('App\tf_student', 'id', 'tf_stud_id');
    }
}
