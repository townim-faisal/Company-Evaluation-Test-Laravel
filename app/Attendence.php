<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    //
    //protected $fillable =['total_month', 'perfect_zone', 'good_zone', 'total_mark', 'member_pin'];
    protected $table = 'attendences';

    /*public function members(){
    	return $this->hasMany('App\Member', 'member_pin');
    }*/
}
