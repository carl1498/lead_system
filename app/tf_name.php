<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tf_name extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tf_name';
    protected $fillable = ['name'];
}
