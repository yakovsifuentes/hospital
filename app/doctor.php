<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    protected $table = 'doctors';

    public function appointment(){
    	return $this->hasMany('App\appointment');
    }
}
