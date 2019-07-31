<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lead_company_type extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'lead_company_type';
    protected $fillable = ['name'];
}
