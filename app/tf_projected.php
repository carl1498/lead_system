<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf_projected extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tf_projected';
    protected $fillable = [
        'tf_name_id', 'program_id', 'amount', 'date_of_payment', 'remarks'
    ];

    public function tf_name(){
        return $this->hasOne('App\tf_name', 'id', 'tf_name_id');
    }

    public function program(){
        return $this->hasOne('App\program', 'id', 'program_id');
    }

    public $timestamps = true;
}
