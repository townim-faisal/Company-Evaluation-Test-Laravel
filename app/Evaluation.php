<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model {

    public function organization(){
        return $this -> belongsTo('App\Organization');
    }

    public function teams(){
        return $this -> hasMany('App\EvaluationTeam');
    }

}