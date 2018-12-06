<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'courses';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
