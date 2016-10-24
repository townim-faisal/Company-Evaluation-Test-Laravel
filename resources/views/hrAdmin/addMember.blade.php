@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Member</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/savemember') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('pin') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Pin Number</label>

                            <div class="col-md-6">
                                <input type="number" class="form-control" name="pin" />

                                @if ($errors->has('pin'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pin') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" />

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Phone Number</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" />

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('joiningdate') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Joining Date</label>

                            <div class="col-md-6">
                                <input type="date" class="form-control" name="joiningdate" />

                                @if ($errors->has('joiningdate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('joiningdate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="form-group{{ $errors->has('teams') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Team</label>

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Select at-least one team</div>
                                    <div class="panel-body">
                                        @foreach($teams as $team)
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="{{$team -> id}}" name="teams[]"/>{{$team -> name}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($errors->has('teams'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('teams') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
