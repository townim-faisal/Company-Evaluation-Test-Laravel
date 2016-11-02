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
use App\Attendence;
use App\Events\EvaluationStart;
use DB;
use Illuminate\Support\Facades\Mail;

class HrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check()){
            if(Auth::user() -> status == 1){
                $role = Auth::user() -> role;
                if($role != 2) {abort(404);}
            } else {
                redirect('/');
            }
        }
    }

    public function addTeam()
    {
        //dd(bcrypt('fahad'));
        $members = Member::where('organization_id', Auth::user()->organization_id)->get();
        $teams = Team::where('organization_id', Auth::user()->organization_id)->get();
        $data = array('members' => $members, 'teams' => $teams);
        return view('hrAdmin/addTeam', $data);
    }

    public function editTeams(){
        $teams = Team::where('organization_id', Auth::user()->organization_id)->get();
        $data = array('teams' => $teams);
        return view('hrAdmin/editTeams', $data);
    }

    public function saveTeam(Request $request)
    {   
        //dd($request);
        $this->validate($request, ['name' => 'required|max:255']);
         DB::transaction(function ($request) use ($request) {
            $team = new Team();
            $team->name = $request->name;
            $team->organization_id = Auth::user()->organization_id;
            $team->coordinator_id = $request->input('undefined') == '' ? null : $request->input('undefined');
            $team->save();
            
            foreach($request->teamMembers as $memberID){
                $member = Member::find($memberID);
                $team->members()->save($member);
            }
        });
        return redirect('/');
        
    }

    public function updateTeams(Request $request){
        DB::transaction(function ($request) use ($request) {
            foreach($request->teams as $id){
                $team = Team::find($id);
                $team->name = $request->input('team'.$id.'name');
                $team->coordinator_id = $request->input('co'.$id) == '' ? null : $request->input('co'.$id);
                $team->save();
                $team->members()->detach();
                $memberIds = $request->input('team'.$id.'members');
                if(!empty($memberIds)){
                    foreach($memberIds as $memberId){
                        $member = Member::find($memberId);
                        $team->members()->save($member);
                    }
                }
            }
        });
        return redirect('/');
    }

    public function addMember()
    {
        $teams = Team::where('organization_id', Auth::user()->organization_id)->get();
        $data = array('teams' => $teams);
        return view('hrAdmin/addMember', $data);
    }

    public function memberList(){
        $members = Member::where('organization_id', Auth::user()->organization_id)->get();
        $data = array('members' => $members);
        return view('hrAdmin/memberList', $data);
    }

    public function editMember(Request $request){
        $member = Member::find($request->id);
        $data = array('member' => $member);
        return view('hrAdmin/editMember', $data);
    }

    public function saveMember(Request $request)
    {
        $organization_id = Auth::user()->organization_id;
        $this->validate($request, [
            'name' => 'required|max:255',
            'pin' => 'required|numeric|unique:members,pin,NULL,id,organization_id,'.$organization_id.'|max:999999999',
            'email' => 'required|email',
            'phone' => 'regex:/((\+)?(\d)+){1}/',
            'joiningdate' => 'date'
            //'teams' => 'required'
        ]);
        DB::transaction(function ($request) use ($request) {
            $member = new Member();
            $member->name = $request->name;
            $member->pin = $request->pin;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->joining_date = $request->joiningdate;
            $member->organization_id = Auth::user()->organization_id;
            $member ->save();
            /*if($member->save()) {
                foreach($request->teams as $team){
                    $team = Team::find($team);
                    $team->members()->save($member);
                }
            }*/
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = 1;
            $user->organization_id = Auth::user()->organization_id;
            $user->status = 1; //user role
            $password = str_random(8);
            $user->password = bcrypt($password);
            $user->save(); 
        });
        return redirect('/');
    }

    public function updateMember(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'email',
            'phone' => 'regex:/((\+)?(\d)+){1}/',
            'joiningdate' => 'date',
            'status' => 'required'
        ]);
        DB::transaction(function ($request) use ($request) {
            $member = Member::find($request->id);
            $member->name = $request->name;
            $member->pin = $request->pin;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->joining_date = $request->joiningdate;
            $member->status = $request->status;
            $member->save();
        });
        return redirect('/');
    }

    public function saveUserAndMember(Request $request)
    {
        //dd($request);
        $email = trim($request->email);
        $user = User::where('email', '=', $email)->count();
        if ($user == 0) {
            DB::transaction(function () use($request, $email){ 
                $pin = trim($request->pin);
                $name = trim($request->name);
                $phone = trim($request->phone);
                $team = trim($request->team);
                $member = Member::where('pin', '=', $pin)->first();
                if ($member == null) {
                    $member = new Member();
                }
                $member->name = $name;
                $member->pin = $pin;
                $member->email = $email;
                $member->phone = $phone;
                $member->organization_id = Auth::user()->organization_id;
                $member->joining_date = $request->joining_date;
                $member->status = 1;
                $member->save();
                /*if($member->save()) {
                    $team = Team::find($team);
                    $team->members()->save($member);
                }*/

                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->role = 1;
                //$user->member_id = $member->id;
                $user->organization_id = Auth::user()->organization_id;
                $user->status = 1;
                $password = str_random(8);
                $user->password = bcrypt($password);
                $user->save();
                //DB::commit();
            }); 
            /*catch (\Exception $e) {
                DB::rollback();
                return "Error. Please Try again";
            }*/
        }
        else {return "User Already exist";}
        /*$data = array('email' => $email, 'password' => $password);
        Mail::later(10, 'regMail', $data, function ($message) use ($email, $name) {
            $message->to($email, $name)->subject('Welcome To OA Mullayon!');
        });*/
        return "Done";
    }

    public function addEvaluation()
    {
        $teams = Team::where('organization_id', Auth::user()->organization_id)->get();
        $data = array('teams' => $teams);
        return view('hrAdmin/addEvaluation', $data);
    }

    public function saveEvaluation(Request $request)
    {
        //dd($request);
        DB::transaction(function ($request) use ($request) {
            $evaluation = new Evaluation();
            $evaluation->name = $request->name;
            $evaluation->organization_id = Auth::user()->organization_id;
            $evaluation->status = 1;
            $evaluation->save();
            
            $evaluationId = Evaluation::all()->last()->id;
            foreach($request->input('teams') as $team){
                $evaluationTeam = new EvaluationTeam();
                $evaluationTeam->evaluation_id = $evaluationId;
                $evaluationTeam->name = $request->input('team'.$team.'name');
                $evaluationTeam->coordinator_id = $request->input('co'.$team) == '' ? null : $request->input('co'.$team);
                $evaluationTeam->save();
                foreach($request->input('team'.$team.'members') as $member){
                    $teamMember = Member::find($member);
                    //dd($teamMember->email);
                    $evaluationTeam->members()->save($teamMember);

                    //mail for evaluation test
                    $email = $teamMember->email;
                    $name = $teamMember->name;
                    $evaluationName = $request->name;
                    $data = ['name' => $name, 'evaluationName' => $evaluationName, 'email' => $email] ;
                    
                    //Event-Listener
                    event(new EvaluationStart($data));

                    //Mail by manually
                    /*Mail::queue( 'evalMail', $data, function ($message) use ($email, $name, $evaluationName) {
                        $message->to($email, $name)->subject('Welcome To '.$evaluationName.' Evaluation Test!');
                    });*/
                }
            }

            //Attitude Related
            $goodNatures = $request->input('goods');
            $j = 1;
            foreach($goodNatures as $goodNature){
                if(!empty($goodNature)){
                    $natureGood = new Nature();
                    $natureGood->evaluation_id = $evaluationId;
                    $natureGood->serial = $j;
                    $natureGood->detail = $goodNature;
                    $natureGood->type = 1;
                    $natureGood->save();
                    $j++;
                }
            }

            //Work Related
            $baddNatures = $request->input('bads');
            //dd($baddNatures);
            foreach($baddNatures as $badNature){
                if(!empty($badNature)){
                    $natureBad = new Nature();
                    $natureBad->evaluation_id = $evaluationId;
                    $natureBad->serial = $j;
                    $natureBad->detail = $badNature;
                    $natureBad->type = 0;
                    $natureBad->save();
                    $j++;
                }
            }

        });
        
        return redirect('/');
    }

    public function addMarks(Request $request){
        $member = Member::where('pin', $request->pin)
            ->where('organization_id', Auth::user()->organization_id)->firstOrFail();
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)->firstOrFail();
        $goods = Nature::where('evaluation_id', $evaluation->id)->get();
        //dd($goods);
        $teams = $member->evaluationTeams()->where('evaluation_id', $evaluation->id)->get();
        $oldMarks = EvaluationMark::where('valuator_id', $member->id)->where('evaluation_id', $evaluation->id)->get();
        $oldNatures = DB::table('members_natures')->select('member_id', 'nature_id', 'nature_point')->where('valuator_id', $member->id)->get();
        $oldExtraMembers = array();
        foreach($teams as $team){
            $teamMembers = EvaluationTeam::find($team->id)->members()->get()->pluck('id');
            $extraMarks = EvaluationMark::where('valuator_id', $member->id)
                ->where('evaluation_id', $evaluation->id)
                ->where('evaluation_team_id', $team->id)
                ->whereNotIn('member_id', $teamMembers)->pluck('mark_with_coordinator', 'member_id');
            $extraMembers = new \SplFixedArray(3);
            foreach(array_keys($extraMarks->toArray()) as $i => $extraId){
                $extraMember = array();
                $exMember = Member::find($extraId);
                $extraMember['id'] = $extraId;
                $extraMember['name'] = $exMember->name;
                $extraMember['pin'] = $exMember->pin;
                $extraMember['mark'] = $extraMarks[$extraId];
                $extraMember['natures'] = DB::table('members_natures')
                    ->select('nature_id')->where('member_id', $exMember->id)
                    ->where('valuator_id', $member->id)->pluck('nature_id');
                $extraMembers[$i] = $extraMember;
                if($i == 2) break;
            }
            $oldExtraMembers['team'.$team->id] = $extraMembers;
        }
        $data = array('evaluator' => $member->id, 'teams' => $teams, 'goods' => $goods, 'oldMarks' => $oldMarks, 'oldNatures' => $oldNatures, 'oldExtraMembers' => $oldExtraMembers);
        //dd($oldNatures);
        return view('hrAdmin/addMarks', $data);
    }

    public function saveMarks(Request $request){
        //dd($request);

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

    public function showResult(){
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
                    ->where('status', 1)->firstOrFail();
        $teams = $evaluation->teams()->get();
        $memberIds = array();
        foreach($teams as $team){
            foreach($team->members()->get() as $member){
                if(!in_array($member->id, $memberIds))
                    $memberIds[]=$member->id;
            }
        }
        //asort($memberIds);
        $results = array();
        foreach($memberIds as $id){
            $result = array();
            $resultMember = Member::find($id);
            $result['member'] = $resultMember;
            $result['markWithCo'] = $resultMember->evaluationMarks()->where('evaluation_id', $evaluation->id)->avg('mark_with_coordinator');
            $result['markWithoutCo'] = $resultMember->evaluationMarks()->where('evaluation_id', $evaluation->id)->avg('mark_without_coordinator');
            $result['goods'] = $resultMember->natures()->where('evaluation_id', $evaluation->id)->where('type', 1)->groupBy('nature_id')->get(array('serial', 'type', DB::raw('sum(nature_point) as sum'), DB::raw('avg(nature_point) as avg'), DB::raw('max(nature_point) as max'), DB::raw('count(*) as count')));
            $result['bads'] = $resultMember->natures()->where('evaluation_id', $evaluation->id)->where('type', 0)->groupBy('nature_id')->get(array('serial', DB::raw('sum(nature_point) as sum'), DB::raw('avg(nature_point) as avg'), DB::raw('max(nature_point) as max'), DB::raw('count(*) as count')));
            
            
            $results[] = $result;
        }
        $goods = Nature::where('evaluation_id', $evaluation->id)->where('type', 1)->get();
        $bads = Nature::where('evaluation_id', $evaluation->id)->where('type', 0)->get();
        
        //total and average attitude & work
        $total_good = [];
        $avg_good =[];
        $total_bad = [];
        $avg_bad =[];

        for($i=0; $i<count($results); $i++){
            //attitude
            if(count($results[$i]['goods']) == 0){
                $total_good[$i] = [];
                $avg_good[$i] = [];
            } else{
                for($x=0; $x<count($results[$i]['goods']);$x++){
                    $total_good[$i][$x] = $results[$i]['goods'][$x]->sum;
                    $avg_good[$i][$x] = $results[$i]['goods'][$x]->avg;
                }
            }
            //work
            if(count($results[$i]['bads']) == 0){
                $total_bad[$i] = [];
                $avg_bad[$i] = [];
            } else{
                for($x=0; $x<count($results[$i]['bads']);$x++){
                    $total_bad[$i][$x] = $results[$i]['bads'][$x]->sum;
                    $avg_bad[$i][$x] = $results[$i]['bads'][$x]->avg;
                }
            }
        }

        //dd($total_good);
        
        $data = array('results' => $results, 'goods' => $goods, 'bads' => $bads, 'name' => $evaluation->name, 'teams' => $teams, 'total_good' => $total_good, 'avg_good' =>$avg_good, 'total_bad' => $total_bad, 'avg_bad' =>$avg_bad);
        return view('hrAdmin/showResult', $data);
    }


    //pogress about members
    public function showProgress(){
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)->firstOrFail();
        $evaluationMarksIDs = DB::table('evaluation_marks')->where('evaluation_id', $evaluation->id)->select('valuator_id')->get();
        //dd($evaluationMarksIDs);
        $memberIDs = [];

        if($evaluationMarksIDs !== null){
            foreach($evaluationMarksIDs as $evaluationMarksID){
                $memberIDs[] = $evaluationMarksID->valuator_id;
                //dd(DB::table('members')->where('id', $evaluationMarksID->member_id)->first());
            }
            //those who attend in evaluation test            
            $memberMarkIDs = array_unique($memberIDs);
        } 

        $members = DB::table('members')->select('id')->where('organization_id', Auth::user()->organization_id)->get();
        foreach($members as $member){
            $totalMembers[] = $member->id;
        }

        //those who are not attend in evaluation test
        $memberNotMarkIDs = array_diff($totalMembers,$memberMarkIDs);
        //dd($memberMarkIDs);
        
        return view('hrAdmin/showProgress', compact('memberMarkIDs', 'memberNotMarkIDs'));
        
    }

    public function showTeamSummary(Request $request){
        $team = EvaluationTeam::find($request->id);
        $data = array('team' => $team);
        return view('hrAdmin/teamSummary', $data);
    }

    public function detailResult(Request $request){
        $member = Member::where('pin', $request->pin)
            ->where('organization_id', Auth::user()->organization_id)->firstOrFail();
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)->firstOrFail();
        $teams = $member->evaluationTeams()->where('evaluation_id', $evaluation->id)->get();
        //dd($teams);
        $teamIds = $teams->pluck('id');
        $teamSize = '';
        $selfEvaluation = array();
        $insideTeam = array();
        if($teams->count() == 1){
            $team = $teams->first();
            $teamSize = $team->members()->count().' Members';
            $selfMark = EvaluationMark::where('evaluation_id', $evaluation->id)
                ->where('valuator_id', $member->id)
                ->where('member_id', $member->id)->firstOrFail();
            $selfEvaluation['wc'] = $selfMark->mark_with_coordinator;
            $selfEvaluation['woc'] = $selfMark->mark_without_coordinator;
            $insideTeam['wc'] = number_format($member->evaluationMarks()->where('evaluation_id', $evaluation->id)->where('evaluation_team_id', $team->id)->avg('mark_with_coordinator'), 2);
            $insideTeam['woc'] = number_format($member->evaluationMarks()->where('evaluation_id', $evaluation->id)->where('evaluation_team_id', $team->id)->avg('mark_without_coordinator'), 2);
        } else{
            $selfMarkWc = '';
            $selfMarkWoc = '';
            $insideTeamWc = '';
            $insideTeamWoc = '';
            foreach($teams as $team){
                $teamSize = $teamSize.$team->members()->count().' Members ('.$team->name.')    ';
                
                $selfMark = EvaluationMark::where('evaluation_id', $evaluation->id)
                    ->where('valuator_id', $member->id)
                    ->where('member_id', $member->id)
                    ->where('evaluation_team_id', $team->id)->firstOrFail();
                
                $selfMarkWc = $selfMarkWc.$selfMark->mark_with_coordinator.'  ('.$team->name.')    ';
                $selfMarkWoc = $selfMarkWoc.$selfMark->mark_without_coordinator.'  ('.$team->name.')    ';
                $insideTeamMarks = $member->evaluationMarks()->where('evaluation_id', $evaluation->id)->where('evaluation_team_id', $team->id)->get();
                $insideTeamWc = $insideTeamWc.number_format($insideTeamMarks->avg('mark_with_coordinator'), 2).'  ('.$team->name.')    ';
                $insideTeamWoc = $insideTeamWoc.number_format($insideTeamMarks->avg('mark_without_coordinator'), 2).'  ('.$team->name.')    ';
            }
            $selfEvaluation['wc'] = $selfMarkWc;
            $selfEvaluation['woc'] = $selfMarkWoc;
            $insideTeam['wc'] = $insideTeamWc;
            $insideTeam['woc'] = $insideTeamWoc;
        }
        $outsideTeamMarks = $member->evaluationMarks()->where('evaluation_id', $evaluation->id)
            ->whereNotIn('evaluation_team_id', $teamIds)->get();
        $outsideTeam = number_format($outsideTeamMarks->avg('mark_with_coordinator'), 2).'  ('.$outsideTeamMarks->count().' person(s))';
        $goods = $member->natures()->where('evaluation_id', $evaluation->id)
            ->groupBy('nature_id')->get(array('detail','serial', 'type', DB::raw('sum(nature_point) as sum'), DB::raw('avg(nature_point) as avg'), DB::raw('max(nature_point) as max'), DB::raw('count(*) as count')));
            //dd($goods);
        $bads = $member->natures()->where('evaluation_id', $evaluation->id)->where('type', 0)
            ->groupBy('nature_id')->get(array('detail', DB::raw('count(*) as count')));
        //dd($goods);
        //$attendence = Attendence::where('member_pin', $member->pin)->first()->total_mark;    
        $data = array(
            'member' => $member,
            'teamSize' => $teamSize,
            'selfEvaluation' => $selfEvaluation,
            'insideTeam' => $insideTeam,
            'outsideTeam' => $outsideTeam,
            'goods' => $goods,
            'bads' => $bads
        );
        return view('hrAdmin/detailResult', $data);
    }

    public function closeEvaluation(){
        $evaluations = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->get();
        foreach($evaluations as $evaluation){
            $evaluation->status = 0;
            $evaluation->save();
        }
        return redirect('/');
    }

    public function uploadMarks(){
        $file = fopen('marks.csv', 'r');
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)->firstOrFail();
        while(!feof($file)){
            $row = fgetcsv($file);
            echo $row[0].'<br>';
            $evaluationMark = new EvaluationMark();
            $evaluationMark->evaluation_id = $evaluation->id;
            $evaluationMark->evaluation_team_id = $row[0]+12;
            $member = Member::where('pin', $row[1])
                ->where('organization_id', Auth::user()->organization_id)->firstOrFail();
            $evaluationMark->member_id = $member->id;
            $evaluationMark->mark_with_coordinator = $row[2];
            $evaluationMark->mark_without_coordinator = $row[3];
            $valuator = Member::where('pin', $row[4])
                ->where('organization_id', Auth::user()->organization_id)->firstOrFail();
            $evaluationMark->valuator_id = $valuator->id;
            $evaluationMark->save();
        }

        fclose($file);
        echo 'All marks uploded successfully';
    }

    public function uploadNatures(){
        $file = fopen('natures.csv', 'r');
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->firstOrFail();
        while(!feof($file)){
            $row = fgetcsv($file);
            echo $row[0].'==='.$row[1].'==='.$row[2].'<br>';
            $member = Member::where('pin', $row[0])->where('organization_id', Auth::user()->organization_id)->firstOrFail();
            $valuator = Member::where('pin', $row[2])->where('organization_id', Auth::user()->organization_id)->firstOrFail();
            $nature = Nature::where('evaluation_id', $evaluation->id)->where('serial', $row[1])->firstOrFail();
            DB::table('members_natures')->insert(['member_id' => $member->id,'nature_id' => $nature->id, 'valuator_id' => $valuator->id]);
        }

        fclose($file);
        echo 'All natures uploded successfully';
    }

    //attendence
    public function getAttendences(){
        //dd(Auth::user()->memberAttendence(1)->total_month);
        $evaluation = Evaluation::where('organization_id', Auth::user()->organization_id)->where('status', 1)->firstOrFail();
        $teams = $evaluation->teams()->get();
        $memberIds = array();
        foreach($teams as $team){
            foreach($team->members()->get() as $member){
                if(!in_array($member->id, $memberIds))
                    $memberIds[]=$member->id;
            }
        }
        $eval_members = array();
        foreach($memberIds as $id){
            $eval_member = Member::find($id);
            $eval_members[] = $eval_member;
        }
        //dd($eval_members);
        $data = ['eval_members' =>$eval_members];
        return view("hrAdmin/attendences", $data);
    }

    //save attendence
    public function saveAttendence(Request $request){
        $this->validate($request, [
            'total_month' => 'required|integer', 
            'perfect_zone' => 'required|integer', 
            'good_zone' => 'required|integer', 
            'total_mark' => 'required'
        ]);

        if(Attendence::where('member_pin', $request->pin)->first() == null){
            $member_attendence = new Attendence();
        }else{
            $member_attendence = Attendence::where('member_pin', $request->pin)->first();
        }
        $member_attendence->total_month = $request->total_month;
        $member_attendence->perfect_zone = $request->perfect_zone;
        $member_attendence->good_zone = $request->good_zone;
        $member_attendence->total_mark = $request->total_mark;
        $member_attendence->member_pin = $request->pin;
        $member_attendence->save();

        return redirect('/attendences');
    }

    //edit attendence
    public function editAttendence(Request $request){
        $member_attendence = Attendence::where('member_pin', $request->pin)->get();
        $member = Member::where('pin', $request->pin)->first();
        //dd($member);
        $data = ['member' => $member, 'member_attendence' => $member_attendence];
        return view('hrAdmin/attendence_single', $data);
    }

    //save all attendences by csv file
    public function saveAllAttendences(Request $request){
        $pin = trim($request->pin);
        $user = Attendence::where('member_pin', $pin)->count();
        if ($user == 0) {
            DB::transaction(function () use($request, $pin){ 
                $member_attendence = new Attendence();
                $member_attendence->total_month = trim($request->total_month);
                $member_attendence->perfect_zone = trim($request->perfect_zone);
                $member_attendence->good_zone = trim($request->good_zone);
                $member_attendence->total_mark = trim($request->total_mark);
                $member_attendence->member_pin = $pin;
                $member_attendence->save();
            });        
        }
        else {
            return "Member's attendence Already exists";
        }
        
        return "Done";
    }

}
