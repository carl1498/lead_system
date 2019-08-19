<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client_bank extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'client_company_type';
    protected $fillable = [
        'bank_name', 'swift_code', 'branch_name', 'account_name', 'account_number',
        'address', 'contact', 'client_id'
    ];

    public $timestamps = true;
}
