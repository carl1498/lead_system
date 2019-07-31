<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class expense_type extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'expense_type';
    protected $fillable = ['name'];

    public $timestamps = true;
}
