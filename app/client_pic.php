<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client_pic extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'client_pic';
    protected $fillable = [
        'name', 'position', 'contact', 'email', 'client_id'
    ];

    public $timestamps = true;
}
