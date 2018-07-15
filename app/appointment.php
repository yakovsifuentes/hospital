<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    protected $table = ['id'];


    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
}
