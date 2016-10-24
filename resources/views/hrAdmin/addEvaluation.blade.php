@extends('layouts.app')

@section('content')
<script>var members = [];var member;</script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Start New Evaluation</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/saveevaluation') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Evaluation Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" />
                            </div>
                        </div>

                        @foreach($teams as $team)
                            <div class="form-group">
                                <div class="evaTeam" teamid="{{$team->id}}">
                                    <div class="evaTeamHeader">
                                        <i class="fa fa-arrow-circle-down"></i>
                                        <input type="hidden" name="teams[]" value="{{$team->id}}">
                                        <input type="text" class="evaTeamName" value="{{$team->name}}" readonly name="team{{$team->id}}name"/>
                                        <button class="btn btnEdit editTeam" type="button">Edit<i class="fa fa-pencil"></i></button>
                                        <button class="btn btnEdit editDone" type="button" style="display:none;">Done<i class="fa fa-check"></i></button>
                                    </div>
                                    <div class="evaTeamBody">
                                        <ul class="list-unstyled">
                                            <li style="display:none;">
                                                <input type="text" class="addMember pin" placeholder="Pin" />
                                                <input type="text" class="addMember name" placeholder="Name" />
                                                <button type="button" class="btn btnAdd pull-right"><i class="fa fa-plus-circle"></i></button>
                                            </li>
                                            <script>
                                                function checkDuply(members, member){
                                                    var duply = false;
                                                    $.each(members, function(){
                                                        if(this.id == member.id)
                                                            return duply = true;
                                                    });
                                                    return duply;
                                                }
                                            </script>
                                            @foreach($team->members as $member)
                                                <script>
                                                    member = {
                                                        id: "{{$member->id}}",
                                                        label: "{{$member->pin}}"+" | " + "{{$member->name}}"
                                                    };
                                                    if(!checkDuply(members, member)) members.push(member);
                                                </script>
                                                <li {{$member->id == $team->coordinator_id ? 'co' : ''}} memberid="{{$member->id}}">
                                                    <label style="display:none;"><input type="radio" name="co{{$team->id}}" value="{{$member->id}}" {{$member->id == $team->coordinator_id ? 'checked' : ''}} /> co </label>
                                                    <span>{{$member->pin}}</span>{{$member->name}}
                                                    <button class="btn btnDelete pull-right" type="button" style="display:none;"><i class="fa fa-minus-circle"></i></button>
                                                    <input type="hidden" value="{{$member->id}}" name="team{{$team->id}}members[]">
                                                </li>
                                            @endforeach
                                            <li {{$team->coordinator_id == null ? 'co' : ''}} style="display:none;">
                                                <label style="display:none;"><input type="radio" name="co{{$team->id}}" value="" {{$team->coordinator_id == null ? 'checked' : ''}}/> co </label><span>&nbsp;&nbsp;</span>No Coordinator
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Attitude Related
                                        <button type="button" class="btn btnAddNature good pull-right"><i class="fa fa-plus-circle"></i></button>
                                        <button type="button" class="btn btnRemoveNature good pull-right"><i class="fa fa-minus-circle"></i></button>
                                    </div>
                                    <div class="panel-body">
                                        <ol class="natureList goodList" start="1">
                                            <li><input type="text" class="form-control" name="goods[]"></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Work Related
                                        <button type="button" class="btn btnAddNature bad pull-right"><i class="fa fa-plus-circle"></i></button>
                                        <button type="button" class="btn btnRemoveNature bad pull-right"><i class="fa fa-minus-circle"></i></button>
                                    </div>
                                    <div class="panel-body">
                                        <ol class="natureList badList" start="2">
                                            <li><input type="text" class="form-control" name="bads[]"></li>
                                        </ol>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var teams = "";
        $('.evaTeam').each(function(){
            teams += '#' + $(this).children('div.evaTeamName').text() + '@';
            teams += $(this).find('li[coId]').attr('coId') + 'co';
            $(this).find('li[memberId]').each(function(){
                teams += $(this).attr('memberId') + ',';
            });
        });
        $('input[name=teamsDetail]').val(teams);
        $( "input.addMember" ).autocomplete({
            source: members,
            focus: function( event, ui ) {
                if($(this).hasClass('pin')){
                    $(this).val(ui.item.label.split('|')[0].trim());
                    $(this).next('input').val(ui.item.label.split('|')[1].trim());
                }
                else {
                    $(this).val(ui.item.label.split('|')[1].trim());
                    $(this).prev('input').val(ui.item.label.split('|')[0].trim());
                }
                return false;
            },
            select: function( event, ui ) {
                if($(this).hasClass('pin')){
                    $(this).val(ui.item.label.split('|')[0].trim());
                    $(this).next('input').val(ui.item.label.split('|')[1].trim());
                }
                else {
                    $(this).val(ui.item.label.split('|')[1].trim());
                    $(this).prev('input').val(ui.item.label.split('|')[0].trim());
                }
                $(this).closest('li').attr('memberId', ui.item.id);
                return false;
            }
        });
    });
</script>
@endsection
