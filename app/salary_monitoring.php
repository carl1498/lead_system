<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salary_monitoring extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'salary_monitoring';
    protected $fillable = [
        'emp_id', 'sal_type', 'rate', 'daily', 'period_from', 'period_to', 'pay_day',
    ];

    public $timestamps = true;
}
