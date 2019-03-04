<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'books';
    protected $fillable = [
        'name', 'stud_id', 'book_type_id', 'branch_id'
    ];

    public $timestamps = true;
    
    public function student(){
        return $this->hasOne('App\students', 'id', 'stud_id');
    }

    public function book_type(){
        return $this->hasOne('App\book_type', 'id', 'book_type_id');
    }
    
    public function branch(){
        return $this->hasOne('App\branches', 'id', 'branch_id');
    }
}
