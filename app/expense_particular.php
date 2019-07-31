<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class expense_particular extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'expense_particular';
    protected $fillable = ['name', 'tin', 'address'];

    public $timestamps = true;
}
