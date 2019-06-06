<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class prev_employment_history extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'prev_employment_history';
    protected $fillable = [
        'emp_id', 'company', 'address', 'hired_date', 
        'until', 'salary', 'designation', 'employment_type'
    ];

    public $timestamps = true;

    public function employee(){
        return $this->hasOne('App\employee', 'id', 'emp_id');
    }
}
