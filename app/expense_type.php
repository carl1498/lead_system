<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class expense_type extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'expense_type';
    protected $fillable = ['name'];

    public $timestamps = true;
}
