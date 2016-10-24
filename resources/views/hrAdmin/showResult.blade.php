@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
        <p>Evaluation Result: {{$name}}</p>
        <a href="{{url('/progress')}}" role="button" class="btn btn-primary">Progress</a>
        </div>

        <div class="panel-body">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Pin</th>
                        <th>Name</th>
                        <th>Mark(wC)</th>
                        <th>Mark(woC)</th>
                        @foreach($goods as $good)
                            <th>{{$good->serial}}</th>
                        @endforeach
                        <th>Attitude(T|A)</th>
                        <th style="background-color: #D2D2C1;">&nbsp;</th>
                        @foreach($bads as $bad)
                            <th>{{$bad->serial}}</th>
                        @endforeach
                        <th>Work(T|A)</th>
                        <th style="background-color: #D2D2C1;">&nbsp;</th>
                        <th>Total|Average</th>
                    </tr>
                    @foreach($results as $key=>$result)
                        <tr>
                            <td><a href="{{ url('/detailresult?pin='.$result['member']->pin) }}" title="detail">{{$result['member']->pin}}</a></td>
                            <td>{{$result['member']->name}}</td>
                            <td>{{number_format($result['markWithCo'], 2)}}</td>
                            <td>{{number_format($result['markWithoutCo'], 2)}}</td>
                            @foreach($goods as $good)
                                <td>
                                    @foreach($result['goods'] as $i => $goodResult)
                                        @if($i < 3 && $goodResult->serial == $good->serial && $goodResult->count >= 3)
                                            <span style="background-color: #8CDB8C; margin: -8px; padding: 8px;">{{$goodResult->sum}}(T)|{{$goodResult->avg}}(A)</span>
                                        @elseif($goodResult->serial == $good->serial)
                                            {{$goodResult->sum}}(T)|{{$goodResult->avg}}(A)
                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
                            <td>{{array_sum($total_good[$key])}}(T)|@if(count($avg_good[$key]) !==0 ){{array_sum($avg_good[$key])/count($avg_good[$key])}}(A)
                            @else {{array_sum($avg_good[$key])}}
                                @endif</td>
                            <td style="background-color: #D2D2C1;">&nbsp;</td>
                            @foreach($bads as $bad)
                                <td>
                                    @foreach($result['bads'] as $j => $badResult)
                                        @if($j < 3 && $badResult->serial == $bad->serial && $badResult->count >= 3)
                                            <span style="background-color: #DB8C8C; margin: -8px; padding: 8px;">{{$badResult->sum}}(T)|{{$badResult->avg}}(A)</span>
                                        @elseif($badResult->serial == $bad->serial)
                                            {{$badResult->sum}}(T)|{{$badResult->avg}}(A)
                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
                            <td>{{array_sum($total_bad[$key])}}(T)|
                            @if(count($avg_bad[$key]) !==0 ){{array_sum($avg_bad[$key])/count($avg_bad[$key])}}(A)@else {{array_sum($avg_bad[$key])}}
                                @endif</td>
                            <td style="background-color: #D2D2C1;">&nbsp;</td>
                            <td>{{array_sum($avg_good[$key])+array_sum($avg_bad[$key])}}(T)|
                            @if(count($avg_good[$key]) !==0 && count($avg_bad[$key]) !==0)
                            {{(array_sum($avg_good[$key])+array_sum($avg_bad[$key]))/(count($avg_good[$key])+count($avg_bad[$key]))}}(A)
                            @else {{(array_sum($avg_good[$key])+array_sum($avg_bad[$key]))}}
                            @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">Team Summary</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>Sl</th>
                                <th>Team Name</th>
                            </tr>
                            @foreach($teams as $i => $team)
                                <tr>
                                    <td>{{$i + 1}}</td>
                                    <td><a href="{{ url('/result/team?id='.$team->id) }}">{{$team->name}}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <form action="{{ url('/closeevaluation') }}" method="get"><button class="btn btn-primary" type="submit" disabled>Close Evaluation</button></form>
            </div>
        </div>
    </div>
</div>
@endsection
