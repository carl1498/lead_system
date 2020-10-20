<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class soa extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'soa';
    protected $fillable = [
        'stud_id', 'soa_fees_id', 'amount_due', 'amount_paid', 'payment_date',
        'emp_id', 'remarks' 
    ];

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id');
    }

    public function soa_fees(){
        return $this->hasOne('App\soa_fees', 'id', 'soa_fees_id');
    }

    public function employee(){
        return $this->hasOne('App\employee', 'id', 'emp_id');
    }
    
    public $timestamps = true;
}
