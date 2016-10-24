<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model {

    public function organization(){
        return $this -> belongsTo('App\Organization');
    }

    /*public function attendence(){
        return $this -> belongsTo('App\Attendence');
    }*/

    public function teams(){
        return $this -> belongsToMany('App\Team', 'teams_members')->withTimestamps();
    }

    public function evaluationTeams(){
        return $this -> belongsToMany('App\EvaluationTeam', 'evaluation_team_members')->withPivot('weight')->withTimestamps();
    }

    public function natures(){
        return $this->belongsToMany('App\Nature', 'members_natures')->withPivot('valuator_id')->withTimestamps();
    }

    public function evaluationMarks(){
        return $this->hasMany('App\EvaluationMark', 'member_id');
    }

    public function evaluationMarksAsValuator(){
        return $this->hasMany('App\EvaluationMark', 'valuator_id');
    }

}