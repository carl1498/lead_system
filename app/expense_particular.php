<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class expense_particular extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'expense_particular';
    protected $fillable = ['name', 'tin', 'address'];

    public $timestamps = true;
}
