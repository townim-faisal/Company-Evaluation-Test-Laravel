<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::check()){
            if(Auth::user() -> status == 1){
                $role = Auth::user() -> role;
                if($role != 3) abort(404);
            }
            else redirect('/');
        }
    }

    public function index()
    {

    }

    public function editUser(Request $request){
        $this->validate($request, [
            'userid' => 'required',
            'role' => 'required',
            'status' => 'required'
        ]);
        $user = User::find($request->userid);
        $user->role = $request->role;
        $user->status = $request->status;
        $user->save();
        return redirect('/');
    }

}
