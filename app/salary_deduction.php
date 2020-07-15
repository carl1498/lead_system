<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salary_deduction extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'salary_deduction';
    protected $fillable = [
        'sal_mon_id', 'cash_advance', 'absence', 'late', 'sss', 'phic',
        'hdmf', 'others', 'undertime', 'man_allocation'
    ];

    public $timestamps = true;
}
