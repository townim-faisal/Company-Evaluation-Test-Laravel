<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    public function organization(){
        return $this -> belongsTo('App\Organization');
    }

    public function members(){
        return $this -> belongsToMany('App\Member', 'teams_members')->withTimestamps();
    }

}