<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class university extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'university';
    protected $fillable = ['name'];

    public $timestamps = true;
}
