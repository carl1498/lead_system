<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'roles';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
