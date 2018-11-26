<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'branches';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
