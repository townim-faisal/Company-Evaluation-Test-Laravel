@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Detail Result</div>

                <div class="panel-body">
                    <p><b>Pin: </b>{{$member->pin}}</p>
                    <p><b>Name: </b>{{$member->name}}</p>
                    <p><b>Team Size: </b>{{$teamSize}}</p>
                    <p><b>Self Evaluation (With Coordinator): </b>{{$selfEvaluation['wc']}}</p>
                    <p><b>Self Evaluation (Without Coordinator): </b>{{$selfEvaluation['woc']}}</p>
                    <p><b>Inside Team (With Coordinator): </b>{{$insideTeam['wc']}}</p>
                    <p><b>Inside Team (Without Coordinator): </b>{{$insideTeam['woc']}}</p>
                    <p><b>Attendence (Total Mark): </b>
                    @if(Auth::user()->memberAttendence($member->pin) !== null)
                    {{Auth::user()->memberAttendence($member->pin)->total_mark}}
                    @endif 
                    </p>
                    {{-- <dt>Outside Team:</dt>
                    <dd>{{$outsideTeam}}</dd> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Natures</div>

                                <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Nature</th>
                                            <th>Total</th>
                                            <th>Average</th>
                                            <th>Highest</th>
                                            <th>Possible Highest</th>
                                        </tr>
                                        @foreach($goods as $good)
                                            <tr>
                                                <td @if($good->type=="0") bgcolor="blue" @endif>{{$good->serial}}</td>
                                                <td>{{$good->detail}}</td>
                                                <td>{{$good->sum}}</td>
                                                <td>{{$good->avg}}</td>
                                                <td>{{$good->max}}</td>
                                                <td>{{$good->count * 3}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Bad Natures</div>

                                <div class="panel-body">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                        <tr>
                                            <th>Nature</th>
                                            <th>Count</th>
                                        </tr>
                                        @foreach($bads->sortByDesc('count') as $bad)
                                            <tr>
                                                <td>{{$bad->detail}}</td>
                                                <td>{{$bad->count}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
