@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">All Members</div>

        <div class="panel-body">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Pin</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Phone</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Edit</th>
                    </tr>
                    @foreach($members->sortBy('pin') as $member)
                        <tr>
                            <td>{{$member->pin}}</td>
                            <td>{{$member->name}}</td>
                            <td>{{$member->email}}</td>
                            <td>{{$member->phone}}</td>
                            <td>{{$member->joining_date}}</td>
                            <td>{{$member->status == 1 ? 'Active' : 'Inactive'}}</td>
                            <td><a class="btn" href="{{ url('/editmember?id='.$member->id) }}">Edit<i class="fa fa-pencil"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
