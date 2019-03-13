<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lost_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'lost_books';
    protected $fillable = [
        'book_id', 'stud_id'
    ];

    public $timestamps = true;

    public function books(){
        return $this->hasOne('App\books', 'id', 'book_id');
    }

    public function student(){
        return $this->hasOne('App\student', 'id', 'stud_id');
    }
}
