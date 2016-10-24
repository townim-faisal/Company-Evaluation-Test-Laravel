@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Teams</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/updateteams') }}">
                        {!! csrf_field() !!}

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
                                            @foreach($team->members->sortBy('pin') as $member)
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
        var members = [];
        var member;
        function checkDuply(members, member){
            var duply = false;
            $.each(members, function(){
                if(this.id == member.id)
                    return duply = true;
            });
            return duply;
        }
        @foreach($teams as $team)
        @foreach($team->members->sortBy('pin') as $member)
        member = {
            id: "{{$member->id}}",
            label: "{{$member->pin}}"+" | " + "{{$member->name}}"
        };
        if(!checkDuply(members, member)) members.push(member);
        @endforeach
        @endforeach

        console.log(members);

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
