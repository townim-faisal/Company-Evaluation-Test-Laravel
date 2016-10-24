<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\Member;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Auth::check();
    }

    public function index()
    {

    }

    public function getAllMembers(){
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)->firstOrFail();
        $memberIds = array();
        $members = array();
        foreach($evaluation->teams()->get() as $team){
            foreach($team->members()->get() as $member){
                if(!in_array($member->id, $memberIds))
                    $memberIds[] = $member->id;
            }
        }
        foreach($memberIds as $memberId){
            $member = Member::find($memberId);
            $members[] = array('id' => $member->id, 'label' => $member->pin.' | '.$member->name);
        }
        return response()->json($members);
    }

}
