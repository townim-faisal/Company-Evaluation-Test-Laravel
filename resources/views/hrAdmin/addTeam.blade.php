@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Team</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/saveteam') }}">
                        {!! csrf_field() !!}
                       
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="name" />

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            <label class="col-md-2 control-label">Coordinator</label>
                            <div class="col-md-10">
                                <select class="form-control" name="coordinator">
                                    <option value="">Select Coordinator</option>
                                    @foreach($members as $member)
                                        <option value="{{$member -> id}}">{{$member -> name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <div class="evaTeam">
                                <label class="col-md-2" style="margin-top:5px; ">Add Member</label>
                                <div class="evaTeamBody">
                                    <ul class="list-unstyled">
                                        <li>
                                            <input type="text" class="addMember pin" placeholder="Pin" />
                                            <input type="text" class="addMember name" placeholder="Name" />
                                            <button type="button" class="btn btnAddMember pull-right"><i class="fa fa-plus-circle"></i></button>
                                        </li>
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Team Name</th>
                            <th>Action</th>
                        </tr>
                        @foreach($teams->sortBy('id') as $team)
                            <tr>
                                <td>{{$team->id}}</td>
                                <td>{{$team->name}}</td>
                                <td><a class="btn" href="{{ url('/editteams') }}">Edit<i class="fa fa-pencil"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

        function checkDuplicate(id, ele){
            var i = 0;
            var duplicate = false;
            ele.children('li').each(function(){
                if(i > 0){
                    if($(this).attr('memberId') == id || $(this).attr('coId') == id)
                        return duplicate = true;
                }
                i++;
            });
            return duplicate;
        }

        
        @foreach($members as $member)
            member = {
                id: "{{$member->id}}",
                label: "{{$member->pin}}"+" | " + "{{$member->name}}"
            };
            if(!checkDuply(members, member)) members.push(member);
        @endforeach

        console.log(members);


        $( "input.addMember" ).autocomplete({
            source: members,
            focus: function( event, ui ) {
                if($(this).hasClass('pin')){
                    $(this).val(ui.item.label.split('|')[0].trim());
                    $(this).next('input').val(ui.item.label.split('|')[1].trim());
                } else {
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

        $('button.btnAddMember').click(function(){
            console.log($(this).closest('li').attr('memberid'));
            if(!checkDuplicate($(this).closest('li').attr('memberid'), $(this).closest('ul'))){
                var html =
                    '<li memberid="'+$(this).closest('li').attr('memberid')+'">'+
                    '<label><input type="radio" name="'+$(this).closest('ul').find('input[type=radio]').attr('name')+'" value="'+$(this).closest('li').attr('memberid')+'"> co-ordinator </label>'+
                    '<span>'+$(this).closest('li').find('input.pin').val()+'</span>'+$(this).closest('li').find('input.name').val()+
                    '<button class="btn btnDelete pull-right" type="button"><i class="fa fa-minus-circle"></i></button>'+
                    '<input type="hidden" value="'+$(this).closest('li').attr('memberid')+'" name="teamMembers[]">'+
                    '</li>';
                $(html).insertAfter($(this).closest('ul').children('li:last'));
            }
        });
    });
</script>

@endsection
