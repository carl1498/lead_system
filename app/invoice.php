<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'invoice';
    protected $fillable = [
        'ref_no_id', 'book_type_id', 'quantity', 'pending'
    ];

    public $timestamps = true;

    
    public function reference_no(){
        return $this->hasOne('App\reference_no', 'id', 'ref_no_id');
    }
    
    public function book_type(){
        return $this->hasOne('App\book_type', 'id', 'book_type_id');
    }
}
