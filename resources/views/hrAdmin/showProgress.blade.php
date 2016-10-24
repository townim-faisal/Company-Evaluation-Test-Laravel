@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div class="panel panel-default">
            	<div class="panel-heading">Attend Members in Current Evaluation Test</div>
                <div class="panel-body">
                	<table class="table table-condensed">
                		<thead>
                			<tr>
                				<th>Pin</th>
                				<th>Name</th>
                				<th>Email</th>
                                <th>Phone</th>
                			</tr>
                		</thead>
                		<tbody>
                			@foreach($memberMarkIDs as $memberMarkID)
                			<tr>
                				<td>{{Auth::user()->memberInfo($memberMarkID)->pin}}</td>
                				<td>{{Auth::user()->memberInfo($memberMarkID)->name}}</td>
                				<td>{{Auth::user()->memberInfo($memberMarkID)->email}}</td>
                                <td>{{Auth::user()->memberInfo($memberMarkID)->phone}}</td>
                			</tr>
                			@endforeach
                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    
        <div class="col-md-5">
            <div class="panel panel-default">
            	<div class="panel-heading">Not Attend Members in Current Evaluation Test</div>
                <div class="panel-body">
                	<table class="table table-condensed">
                		<thead>
                			<tr>
                				<th>Pin</th>
                				<th>Name</th>
                				<th>Email</th>
                                <th>Phone</th>
                			</tr>
                		</thead>
                		<tbody>
                			@foreach($memberNotMarkIDs as $memberNotMarkID)
                			<tr>
                				<td>{{Auth::user()->memberInfo($memberNotMarkID)->pin}}</td>
                				<td>{{Auth::user()->memberInfo($memberNotMarkID)->name}}</td>
                				<td>{{Auth::user()->memberInfo($memberNotMarkID)->email}}</td>
                                <td>{{Auth::user()->memberInfo($memberNotMarkID)->phone}}</td>
                			</tr>
                			@endforeach
                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
