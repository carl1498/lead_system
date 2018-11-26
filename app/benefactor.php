<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class benefactor extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'benefactors';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
