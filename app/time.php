<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class time extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'time';
    protected $fillable = ['name'];

    public $timestamps = true;
}
