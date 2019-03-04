<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class request_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'request_books';
    protected $fillable = [
        'p_request_id', 'previous_pending', 'quantity', 'pending', 'remarks'
    ];

    public $timestamps = true;
    
    public function pending_request(){
        return $this->hasOne('App\pending_request', 'id', 'p_request_id');
    }
}
