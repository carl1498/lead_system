<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sec_bond extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'sec_bond';
    protected $fillable = [
        'tf_stud_id', 'amount', 'date', 'remarks'
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id');
    }
}
