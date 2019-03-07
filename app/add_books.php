<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class add_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'add_books';
    protected $fillable = [
        'invoice_id', 'book_type_id', 'quantity', 'previous_pending', 'pending', 'book_no_start', 'book_no_end', 'remarks'
    ];

    public $timestamps = true;

    public function reference_no(){
        return $this->hasOne('App\reference_no', 'id', 'invoice_ref_id');
    }

    public function book_type(){
        return $this->hasOne('App\book_type', 'id', 'book_type_id');
    }
}
