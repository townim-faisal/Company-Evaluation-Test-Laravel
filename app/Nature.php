<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nature extends Model {

    public function evaluation(){
        return $this->belongsTo('App\Evaluation');
    }

}