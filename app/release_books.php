<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class release_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'release_books';
    protected $fillable = [
        'p_request_id', 'quantity', 'book_no_low', 'book_no_high', 'remarks'
    ];

    public $timestamps = true;
    
    public function pending_request(){
        return $this->hasOne('App\pending_request', 'id', 'p_request_id');
    }
}
