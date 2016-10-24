@extends('layouts.app')

@section('content')
<div class="container">
    @if (count($errors) > 0)
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
        </div>    
    </div>
    @endif
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Attendence of {{$member->name}}</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/saveattendence') }}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-md-4 control-label">Total Month</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="total_month" value="" />
                                <input type="hidden" class="form-control" name="pin" value="{{$member->pin}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Perfect Zone</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="perfect_zone" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Good Zone</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="good_zone" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Total Mark</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="total_mark" value="" />
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