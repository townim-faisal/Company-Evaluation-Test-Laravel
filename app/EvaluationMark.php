<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationMark extends Model {

	protected $table = 'evaluation_marks';
	
    public function evaluation(){
        return $this->belongsTo('App\Evaluation');
    }

}