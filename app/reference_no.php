<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reference_no extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'reference_no';
    protected $fillable = [
        'lead_ref_no', 'invoice_ref_no'
    ];

    public $timestamps = true;
}
