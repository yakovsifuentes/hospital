<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class specialty extends Model
{
    protected $table = 'specialtys';

    public function doctor(){
    	return $this->hasOne('App\doctor');
    }
}
