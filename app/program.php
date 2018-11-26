<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class program extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'programs';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
