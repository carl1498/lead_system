<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class order extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'order';
    protected $fillable = [
        'order_type_id', 'client_id', 'no_of_orders', 'no_of_hires', 'interview_date', 'remarks'
    ];

    public $timestamps = true;

    public function order_type(){
        return $this->hasOne('App\order_type', 'id', 'order_type_id');
    }

    public function client(){
        return $this->hasOne('App\client', 'id', 'client_id');
    }
}
