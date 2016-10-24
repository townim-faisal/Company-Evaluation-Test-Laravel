@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Add Evaluation Marks</div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Natures</div>

                        <div class="panel-body">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Description</th>
                                    </tr>
                                    @foreach($goods as $good)
                                        <tr>
                                            <td>{{$good->serial}}</td>
                                            <td>{{$good->detail}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/savemarks') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="valuator" value="{{$evaluator}}">
                @foreach($teams as $team)
                    <input type="hidden" name="teams[]" value="{{$team->id}}">
                    @if($team->coordinator_id == null || $team->coordinator_id == '')
                        <div class="evaTeamHeader"> {{$team->name}} </div>
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <th>Pin</th>
                                    <th>Name</th>
                                    <th>Mark</th>
                                    <th>Good Natures(At-list three)</th>
                                    <th>Bad Natures</th>
                                </tr>
                                @foreach($team->members->sortBy('pin') as $member)
                                    <tr>
                                        <input type="hidden" name="team{{$team->id}}members[]" value="{{$member->id}}">
                                        <td>{{$member->pin}}</td>
                                        <td>{{$member->name}}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control markInput" name="team{{$team->id}}member{{$member->id}}markwc"
                                                @foreach($oldMarks as $oldMark)
                                                    @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                                        value="{{$oldMark->mark_with_coordinator}}"
                                                    @endif
                                                @endforeach
                                            />
                                            <input type="hidden" name="team{{$team->id}}member{{$member->id}}markwoc">
                                        </td>
                                        <td>
                                            @foreach($goods as $good)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="team{{$team->id}}member{{$member->id}}natures[]" value="{{$good->id}}"
                                                        @foreach($oldNatures as $oldNature)
                                                            @if($oldNature->member_id == $member->id && $oldNature->nature_id == $good->id) checked @endif
                                                        @endforeach
                                                    /> {{$good->serial}}
                                                </label>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($bads as $bad)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="team{{$team->id}}member{{$member->id}}natures[]" value="{{$bad->id}}"
                                                        @foreach($oldNatures as $oldNature)
                                                            @if($oldNature->member_id == $member->id && $oldNature->nature_id == $bad->id) checked @endif
                                                        @endforeach
                                                    /> {{$bad->serial}}
                                                </label>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="evaTeamHeader"> {{$team->name}} (With Coordinator)</div>
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th>Pin</th>
                                <th>Name</th>
                                <th>Mark</th>
                                <th>Good Natures(At-list three)</th>
                                <th>Bad Natures</th>
                            </tr>
                            @foreach($team->members->sortBy('pin') as $member)
                                <tr>
                                    <input type="hidden" name="team{{$team->id}}members[]" value="{{$member->id}}">
                                    <td>{{$member->pin}}</td>
                                    <td>{{$member->name}}</td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control markInput" name="team{{$team->id}}member{{$member->id}}markwc"
                                            @foreach($oldMarks as $oldMark)
                                                @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                                    value="{{$oldMark->mark_with_coordinator}}"
                                                @endif
                                            @endforeach
                                        />
                                    </td>
                                    <td>
                                        @foreach($goods as $good)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="team{{$team->id}}member{{$member->id}}natures[]" value="{{$good->id}}"
                                                    @foreach($oldNatures as $oldNature)
                                                        @if($oldNature->member_id == $member->id && $oldNature->nature_id == $good->id) checked @endif
                                                    @endforeach
                                                /> {{$good->serial}}
                                            </label>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($bads as $bad)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="team{{$team->id}}member{{$member->id}}natures[]" value="{{$bad->id}}"
                                                    @foreach($oldNatures as $oldNature)
                                                        @if($oldNature->member_id == $member->id && $oldNature->nature_id == $bad->id) checked @endif
                                                    @endforeach
                                                /> {{$bad->serial}}
                                            </label>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="evaTeamHeader"> {{$team->name}} (Without Coordinator)</div>
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <th>Pin</th>
                                <th>Name</th>
                                <th>Mark</th>
                                <th>Good Natures(At-list three)</th>
                                <th>Bad Natures</th>
                            </tr>
                            @foreach($team->members->sortBy('pin') as $member)
                                @if($member->id != $team->coordinator_id)
                                    <tr>
                                        <td>{{$member->pin}}</td>
                                        <td>{{$member->name}}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control markInput" name="team{{$team->id}}member{{$member->id}}markwoc"
                                                @foreach($oldMarks as $oldMark)
                                                    @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                                        value="{{$oldMark->mark_without_coordinator}}"
                                                    @endif
                                                @endforeach
                                            />
                                        </td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                    <div class="evaTeamHeader"> {{$team->name}} (Extra three persons)</div>
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>Pin</th>
                                <th>Name</th>
                                <th>Mark</th>
                                <th>Good Natures(At-list three)</th>
                                <th>Bad Natures</th>
                            </tr>
                            @for($i = 1; $i <= 3; $i++)
                                <tr>
                                    <input type="hidden" name="team{{$team->id}}members[]" value="">
                                    <td><input type="number" class="form-control addMember pin" /></td>
                                    <td><input type="text" class="form-control addMember name" /></td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control markInput" name="team{{$team->id}}membermarkwc">
                                        <input type="hidden" name="team{{$team->id}}membermarkwoc">
                                    </td>
                                    <td>
                                        @foreach($goods as $good)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="team{{$team->id}}membernatures[]" value="{{$good->id}}"> {{$good->serial}}
                                            </label>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($bads as $bad)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="team{{$team->id}}membernatures[]" value="{{$bad->id}}"> {{$bad->serial}}
                                            </label>
                                        @endforeach
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <br><br>
                @endforeach
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var members = [];
        $.ajax({
            url: "{{ url('/allmembers') }}",
            dataType: "json",
            async: false,
            success: function( data ) {
                members = data;
            }
        });
        $( "input.addMember" ).autocomplete({
            source: members,
            focus: function( event, ui ) {
                if($(this).hasClass('pin')){
                    $(this).val(ui.item.label.split('|')[0].trim());
                    $(this).parent('td').next('td').children('input').val(ui.item.label.split('|')[1].trim());
                }
                else {
                    $(this).val(ui.item.label.split('|')[1].trim());
                    $(this).parent('td').prev('td').children('input').val(ui.item.label.split('|')[0].trim());
                }
                return false;
            },
            select: function( event, ui ) {
                if($(this).hasClass('pin')){
                    $(this).val(ui.item.label.split('|')[0].trim());
                    $(this).parent('td').next('td').children('input').val(ui.item.label.split('|')[1].trim());
                }
                else {
                    $(this).val(ui.item.label.split('|')[1].trim());
                    $(this).parent('td').prev('td').children('input').val(ui.item.label.split('|')[0].trim());
                }
                $(this).closest('tr').children('input[type=hidden]').val(ui.item.id);
                var markInput = $(this).closest('tr').find('input[name*=member][type=number]');
                var markInputWoc = $(this).closest('tr').find('input[name*=member][type=hidden]');
                var natureInput = $(this).closest('tr').find('input[name*=member][type=checkbox]');
                var team = markInput.attr('name').split('member')[0];
                markInput.attr('name', team+'member'+ui.item.id+'markwc');
                markInputWoc.attr('name', team+'member'+ui.item.id+'markwoc');
                natureInput.attr('name', team+'member'+ui.item.id+'natures[]');
                return false;
            }
        });
    });
</script>
@endsection
