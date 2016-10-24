<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationTeam extends Model {

    public function evaluation(){
        return $this -> belongsTo('App\Evaluation');
    }

    public function members(){
        return $this -> belongsToMany('App\Member', 'evaluation_team_members')->withPivot('weight')->withTimestamps();
    }

}