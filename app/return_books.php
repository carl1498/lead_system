<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class return_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'return_books';
    protected $fillable = [
        'book_id'
    ];

    public $timestamps = true;
    
    public function books(){
        return $this->hasOne('App\books', 'id', 'book_id');
    }

    public function student(){
        return $this->hasOne('App\students', 'id', 'stud_id');
    }
}
