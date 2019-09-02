<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_type extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'order_type';
    protected $fillable = [
        'name'
    ];
}
