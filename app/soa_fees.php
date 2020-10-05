<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class soa_fees extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'soa_fees';
    protected $fillable = [
        'name'
    ];
    
    public $timestamps = true;
}
