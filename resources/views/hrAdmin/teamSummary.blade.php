@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Team Summary: {{$team->name}} (With Coordinator)</div>

        <div class="panel-body" style="overflow:auto;">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Pin</th>
                        <th>Name</th>
                        @foreach($team->members as $member)
                            <th>{{$member->pin}}</th>
                        @endforeach
                    </tr>
                    @foreach($team->members as $member)
                        <tr>
                            <td><a href="{{ url('/detailresult?pin='.$member->pin) }}" title="detail">{{$member->pin}}</a></td>
                            <td>{{$member->name}}</td>
                            @foreach($team->members as $markMember)
                                @if($member->id == $markMember->id)
                                    <td style="background-color: #00b3ee">
                                        @foreach($member->evaluationMarks as $mark)
                                            @if($mark->evaluation_team_id == $team->id && $mark->member_id == $markMember->id && $mark->valuator_id == $member->id)
                                                {{$mark->mark_with_coordinator}}
                                            @endif
                                        @endforeach
                                    </td>
                                @else
                                    <td>
                                        @foreach($member->evaluationMarksAsValuator as $mark)
                                            @if($mark->evaluation_team_id == $team->id && $mark->member_id == $markMember->id && $mark->valuator_id == $member->id)
                                                {{$mark->mark_with_coordinator}}
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">Team Summary: {{$team->name}} (Without Coordinator)</div>

        <div class="panel-body" style="overflow:auto;">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Pin</th>
                        <th>Name</th>
                        @foreach($team->members as $member)
                            <th>{{$member->pin}}</th>
                        @endforeach
                    </tr>
                    @foreach($team->members as $member)
                        <tr>
                            <td><a href="{{ url('/detailresult?pin='.$member->pin) }}" title="detail">{{$member->pin}}</a></td>
                            <td>{{$member->name}}</td>
                            @foreach($team->members as $markMember)
                                @if($member->id == $markMember->id)
                                    <td style="background-color: #00b3ee">
                                        @foreach($member->evaluationMarks as $mark)
                                            @if($mark->evaluation_team_id == $team->id && $mark->member_id == $markMember->id && $mark->valuator_id == $member->id && $mark->member_id != $team->coordinator_id)
                                                {{$mark->mark_without_coordinator}}
                                            @endif
                                        @endforeach
                                    </td>
                                @else
                                    <td>
                                        @foreach($member->evaluationMarksAsValuator as $mark)
                                            @if($mark->evaluation_team_id == $team->id && $mark->member_id == $markMember->id && $mark->valuator_id == $member->id && $mark->member_id != $team->coordinator_id)
                                                {{$mark->mark_without_coordinator}}
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
