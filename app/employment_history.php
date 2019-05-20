<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employment_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'employment_history';
    protected $fillable = [
        'emp_id', 'hired_date', 'until'
    ];

    public $timestamps = true;
}
