<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'expense';
    protected $fillable = ['expense_type_id', 'expense_particular_id', 'branch_id',
        'lead_company_type_id', 'date', 'amount', 'vat', 'input_tax', 'remarks'];

    public $timestamps = true;

    public function type(){
        return $this->hasOne('App\expense_type', 'id', 'expense_type_id');
    }
    
    public function particular(){
        return $this->hasOne('App\expense_particular', 'id', 'expense_particular_id');
    }
    
    public function company_type(){
        return $this->hasOne('App\lead_company_type', 'id', 'lead_company_type_id');
    }
}
