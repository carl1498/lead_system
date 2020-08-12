<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salary_income extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'salary_income';
    protected $fillable = [
        'sal_mon_id', 'basic_rate', 'cola', 'acc_allowance', 'adjustment', 'transpo_allowance',
        'market_comm', 'jap_comm', 'reg_ot', 'rd_ot', 'thirteenth', 'leg_hol', 'spcl_hol', 
        'leg_hol_ot', 'spcl_hol_ot', 'transpo_days'
    ];

    public $timestamps = true;
}
