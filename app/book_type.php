<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class book_type extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'book_type';
    protected $fillable = [
        'name', 'description'
    ];

    public $timestamps = true;
}
