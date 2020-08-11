<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class emp_salary extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'emp_salary';
    protected $fillable = [
        'emp_id', 'sal_type', 'rate', 'daily', 'cola', 'acc_allowance', 'transpo_allowance',
        'sss', 'phic', 'hdmf'
    ];

    public function employee(){
        return $this->hasOne('App\employee', 'id', 'emp_id');
    }

    public $timestamps = true;
}
