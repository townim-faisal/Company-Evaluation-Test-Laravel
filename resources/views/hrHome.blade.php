@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <i class="fa fa-step-backward" aria-hidden="true" id="toggle_sidebar"></i>
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">Evaluations</a></li>
                <li><a href="#">Teams</a></li>
                <li><a href="#">Members</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                        <p><b>Members</b></p>
                            <a href="{{ url('/addmember') }}" class="btn btn-primary btn-lg btn-block">Add Member</a><br>
                            <a href="{{ url('/memberlist') }}" class="btn btn-default btn-lg btn-block">Edit Member</a><br>
                            <a class="btn btn-primary btn-lg btn-block" href="{{ URL::asset('files/evaTeam.csv') }}" download>
                              Download Sample Member Uploading Sheet
                            </a>
                            <input type="file" class="btn btn-default btn-lg btn-block" id="csvMembers" /><br>
                            <button class="btn btn-primary btn-lg btn-block" type="button" id="uploadMembers">Upload</button>
                        </div>
                        
                        <div class="col-md-6">
                        <p><b>Team And Evaluation</b></p>
                            <a href="{{ url('/addteam') }}" class="btn btn-primary btn-lg btn-block">Add Team</a><br>
                            <a href="{{ url('/editteams') }}" class="btn btn-default btn-lg btn-block">Edit Teams</a><br>
                            @if($numberOfActiveMember > 0)
                                @if($activeEvaluation < 1)
                                    <a href="{{ url('/addevaluation') }}" class="btn btn-primary btn-lg btn-block">Start New Evaluation</a>
                                @else
                                    <a href="{{ url('/showresult') }}" class="btn btn-primary btn-lg btn-block">Show Evaluation Result</a>
                                    <br>
                                    <a href="{{ url('/attendences') }}" class="btn btn-default btn-lg btn-block">Add Member's Attendences</a>
                                    {{-- <br>
                                    <a href="{{url('/progress')}}" class="btn btn-primary btn-lg btn-block">Progress</a> --}}
                                    <br>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Entry Evaluation Marks</div>
                                        <div class="panel-body">
                                            <form class="form-horizontal" role="form" method="GET" action="{{ url('/addmarks') }}">

                                                <div class="form-group{{ $errors->has('pin') ? ' has-error' : '' }}">
                                                    <label class="col-md-4 control-label">Pin Number</label>

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="pin" />

                                                        @if ($errors->has('pin'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('pin') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button class="btn btn-primary" type="submit">Go</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <br>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div id="membersTable"></div>
                        <div id="membersTableBtn"></div>
                    </div>
                </div>
                        
                {!! csrf_field() !!}
            </div>
        </div>
    </div>
</div>

@endsection