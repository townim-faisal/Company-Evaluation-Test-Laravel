<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\EvaluationMark;
use App\EvaluationTeam;
use App\Http\Requests;
use App\Nature;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Member;
use App\Team;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Auth::user() -> status;
        if($status == 1)
        {
            $role = Auth::user() -> role;
            $numberOfActiveMember = Member::where('organization_id', Auth::user()->organization_id)->where('status', 1)->count();
            $activeEvaluation = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->count();
            $hrData = array('numberOfActiveMember' => $numberOfActiveMember, 'activeEvaluation' => $activeEvaluation);
            $suData = array('users' => User::all());
            switch($role){
                //user role
                case 1: {
                    if ($activeEvaluation == 1) {
                        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->firstOrFail();
                        $member = Member::where('email', Auth::user()->email)->first();
                        $oldMarks = EvaluationMark::where('valuator_id', $member->id)->where('evaluation_id', $evaluation->id)->count();
                        //dd($member->id);
                        $userData = array('active' => $activeEvaluation, 'oldMarks' => $oldMarks);
                        if ($oldMarks == 0) {
                            $goods = Nature::where('evaluation_id', $evaluation->id)->where('type', 1)->get();
                            //$member = Member::where('email', Auth::user()->email)->first();
                            $teams = $member->evaluationTeams()->where('evaluation_id', $evaluation->id)->get();
                            //dd();
                            $userData = array('active' => $activeEvaluation, 'oldMarks' => $oldMarks, 'goods' => $goods, 'teams' => $teams, 'evaluator' => $member->id);
                        }
                    } else {
                        $userData = array('active' => 0);
                        return view('userHome', $userData);
                    }
                    return view('userHome', $userData);
                    break;
                }
                //hr admin role
                case 2: return view('hrHome', $hrData); break;
                //super admin role
                case 3: return view('superAdminHome', $suData); break;
                default: return view('home'); break;
            }
        }
        else return view('inactive');
    }

}
