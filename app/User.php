<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Member;
use App\Attendence;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'organization_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function organization(){
        return $this->belongsTo('App\Organization');
    }

    public function memberInfo($id)
    {
        $memberInfo = DB::table('members')->where('id', $id)->first();
        return $memberInfo;
    }

    //get attendence by member pin
    public function memberAttendence($pin){
        $pin_no = (int)$pin;
        $member_attendence = Attendence::where('member_pin', $pin_no)->first();
        if($member_attendence !== null) {return $member_attendence;}
    }
}
