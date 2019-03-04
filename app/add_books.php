<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class add_books extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'add_books';
    protected $fillable = [
        'invoice_id', 'quantity', 'previous_pending', 'pending', 'book_no_low', 'book_no_high', 'remarks'
    ];

    public $timestamps = true;

    public function invoice(){
        return $this->hasOne('App\invoice', 'id', 'invoice_id');
    }
}
