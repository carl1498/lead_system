<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pending_request extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'pending_request';
    protected $fillable = [
        'book_type_id', 'branch_id', 'pending'
    ];

    public $timestamps = true;
    
    public function book_type(){
        return $this->hasOne('App\book_type', 'id', 'book_type_id');
    }
    
    public function branch(){
        return $this->hasOne('App\branches', 'id', 'branch_id');
    }
}
