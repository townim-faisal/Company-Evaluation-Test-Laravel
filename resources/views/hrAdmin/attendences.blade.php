@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
                    <div class="panel-heading">All Member's Attendences
                    <a class="btn pull-right" href="{{ URL::asset('files/attendence.csv') }}" download>
                      Download Sample attendence sheet
                    </a>
                    </div>

                    <div class="panel-body">
                    <input type="file" class="btn btn-default btn-lg btn-block" id="csvMembersAttendences" />
                    <button class="btn btn-primary btn-lg btn-block" type="button" id="uploadMembersAttendences">Upload</button>
                    <br>

                    <div id="membersAttendencesTable"></div>
                    <div id="membersAttendencesTableBtn"></div>
                    {!! csrf_field() !!}
                    <br>
                    
		            <table class="table table-bordered table-hover">
		                <tbody>
		                    <tr>
		                        <th>Pin</th>
		                        <th>Name</th>
		                        <th>Total Month</th>
		                        <th>Perfect Zone</th>
		                        <th>Good Zone</th>
		                        <th>Total Mark</th>
		                    </tr>
		                    @foreach($eval_members as $eval_member) 
		                    <tr>
                            	<td><a class="btn" href="{{ url('/attendence?pin='.$eval_member->pin) }}">{{$eval_member->pin}}</a></td>
                            	<td>{{$eval_member->name}}</td>
                            	<td>
                                @if(Auth::user()->memberAttendence($eval_member->pin) !== null)
                                {{Auth::user()->memberAttendence($eval_member->pin)->total_month}}
                                @endif
                                </td>
                            	<td>
                                @if(Auth::user()->memberAttendence($eval_member->pin) !== null)
                                {{Auth::user()->memberAttendence($eval_member->pin)->perfect_zone}}
                                @endif   
                                </td>
                            	<td>
                                @if(Auth::user()->memberAttendence($eval_member->pin) !== null)
                                {{Auth::user()->memberAttendence($eval_member->pin)->good_zone}}
                                @endif    
                                </td>
                            	<td>
                                @if(Auth::user()->memberAttendence($eval_member->pin) !== null)
                                {{Auth::user()->memberAttendence($eval_member->pin)->total_mark}}
                                @endif    
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection