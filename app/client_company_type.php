<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client_company_type extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'client_company_type';
    protected $fillable = [
        'name'
    ];
}
