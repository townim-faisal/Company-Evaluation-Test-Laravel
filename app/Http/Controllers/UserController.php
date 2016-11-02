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
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check()){
            if(Auth::user() -> status == 1){
                $role = Auth::user() -> role;
                if($role != 1) abort(404);
            }
            else redirect('/');
        }
    }

    public function index()
    {

    }

    public function saveMarks(Request $request){
        //dd($request->input('valuator'));
        DB::transaction(function ($request) use ($request){
            $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->firstOrFail();
            //dd(EvaluationMark::where('evaluation_id', $evaluation->id)->where('valuator_id', $request->input('valuator'))->firstOrFail());
            EvaluationMark::where('valuator_id', $request->input('valuator'))->where('evaluation_id', $evaluation->id)->delete();
            $teams = array_unique($request->input('teams'));
            //dd($teams);
            foreach($teams as $team){
                $members = array_unique($request->input('team'.$team.'members'));
                //dd($members);
                foreach($members as $member){
                    if($member != null && $member != ''){
                        /*if($request->input('team'.$team.'member'.$member.'markwoc') != null && $request->input('team'.$team.'member'.$member.'markwoc') != ''){*/
                            /*$evaluationMark = EvaluationMark::where('evaluation_id', $evaluation->id)->where('valuator_id', $request->input('valuator'))->firstOrFail();

                            if($evaluationMark == null) */
                            $evaluationMark = new EvaluationMark();
                            $evaluationMark->evaluation_id = $evaluation->id;
                            $evaluationMark->evaluation_team_id = $team;
                            $evaluationMark->member_id = $member;
                            $evaluationMark->mark_with_coordinator = $request->input('team'.$team.'member'.$member.'markwc') == null || $request->input('team'.$team.'member'.$member.'markwc') == '' ? '' : $request->input('team'.$team.'member'.$member.'markwc');
                            $evaluationMark->mark_without_coordinator = $request->input('team'.$team.'member'.$member.'markwoc') == null || $request->input('team'.$team.'member'.$member.'markwoc') == '' ? '' : $request->input('team'.$team.'member'.$member.'markwoc');
                            $evaluationMark->valuator_id = $request->input('valuator');
                            $evaluationMark->save();

                            $oldNatures = Member::find($member)->natures()->where('evaluation_id', $evaluation->id)->where('valuator_id', $request->input('valuator'))->where('member_id', $member)->pluck('members_natures.id');
                            
                            DB::table('members_natures')->whereIn('id', $oldNatures)->delete();
                            //dd($oldNatures);
                            $natures = $request->input('natures');
                            $nature_points = $request->input('team'.$team.'member'.$member.'natures');
                            //dd($nature_points);
                            if(!empty($natures)) {
                                //dd($natures);
                                foreach ($natures as $key=>$value) {
                                    //dd($nature_points[$key]);
                                    if(array_key_exists($key,$nature_points) && $nature_points[$key] !== '0'){
                                        //dd(Member::find($member)->natures());
                                        Member::find($member)->natures()->save(
                                            Nature::find($value), 
                                            array(
                                                'valuator_id' => $request->input('valuator'), 
                                                'nature_point' => $nature_points[$key]
                                            )
                                        );
                                    }
                                }
                            }
                        /*}*/
                    }
                }
            }
        });
        return redirect('/');
    }
}
