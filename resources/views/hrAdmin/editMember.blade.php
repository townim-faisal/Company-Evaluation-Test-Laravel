@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Member</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/updatemember') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$member->id}}">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{$member->name}}" />

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
                                <input type="number" class="form-control" name="pin" value="{{$member->pin}}" />

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
                                <input type="email" class="form-control" name="email" value="{{$member->email}}" />

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
                                <input type="text" class="form-control" name="phone" value="{{$member->phone}}" />

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
                                <input type="date" class="form-control" name="joiningdate" value="{{$member->joining_date}}" />

                                @if ($errors->has('joiningdate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('joiningdate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Status</label>

                            <div class="col-md-6">
                                <label class="checkbox-inline">
                                    <input type="radio" name="status" value="1" {{$member->status == 1 ? 'checked' : ''}} /> Active
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="status" value="0" {{$member->status == 0 ? 'checked' : ''}} /> Inactive
                                </label>
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

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
