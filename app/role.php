<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'roles';
    protected $fillable = [
        'name'
    ];

    public function employee(){
        return $this->hasMany('App\employee', 'role_id', 'id');
    }

    public $timestamps = true;
}
