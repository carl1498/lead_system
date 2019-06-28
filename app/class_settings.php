<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class class_settings extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'class_settings';
    protected $fillable = [
        'sensei_id', 'start_date', 'end_date'
    ];

    public $timestamps = true;
}
