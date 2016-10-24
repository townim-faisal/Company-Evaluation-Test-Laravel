@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading ">Add Evaluation Marks</div>
        <div class="panel-body Prev">
            <div class="row">
                <div class="col-md-12">
                <p>এই ধাপে আমরা কর্মক্ষেত্রে মানুষের পজেটিভ কিছু বৈশিষ্ট্যের তালিকা করতে চেষ্টা করেছি। আপনার টিমের সদস্যের মধ্যে যেসকল গুণাবলিগুলো লক্ষ্য করছেন সেগুলো নির্দিষ্ট সদস্যের ঘরে নম্বর প্রদানের মাধ্য দিয়ে উল্লেখ্য করুণ। উল্লেখিত কোন গুণ কারো মধ্যে, </p>
                <p>প্রবল মাত্রায় থাকলেঃ    ৩</p>
                <p>সাধারণ মাত্রায় থাকলেঃ   ২</p>
                <p>স্বল্প মাত্রায় থাকেলঃ       ১</p>
                </div>
            </div>
        </div>

        <div class="panel-body Next">
            <div class="row">
                <div class="col-md-12">
                <p>এই ধাপে আপনার হাতে কিছু নম্বর (মেন্টর সহ ৬০ এবং মেন্টর ছাড়া ৫০) দেওয়া হচ্ছে। এই নম্বর আপনি নির্দিষ্ট সদস্যের (নিজেকে সহ) আচার-আচরণ, কাজের দক্ষতা, টিমে বা কোম্পানিতে তার অবদান, সহযোগিতা মূলক মনোভাব, প্রতিষ্ঠান ও কাজের প্রতি শ্রদ্ধা বোধ প্রভৃতি বিষয়গুলোকে মাথায় রেখে বণ্টন করতে পারেন। </p>
                </div>
            </div>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/savemarks') }}">
        {!! csrf_field() !!}
        <div class="panel-body Prev table-responsive">
            <input type="hidden" name="valuator" value="{{$evaluator}}">
            @foreach($teams as $team)
                <input type="hidden" name="teams[]" value="{{$team->id}}">
                <div class="evaTeamHeader"> {{$team->name}}</div>
                <table class="table table-bordered table-hover">
                    <tbody>
                    <tr>
                        <th>Serial No.</th>
                        <th>Natures</th>
                        @foreach($team->members->sortBy('pin') as $member)
                        <input type="hidden" name="team{{$team->id}}members[]" value="{{$member->id}}">
                        <th>{{$member->name}} ({{$member->pin}})</th>
                        @endforeach
                    </tr>
                    @foreach($goods as $good)
                        <tr>
                            <td @if($good->type=="0") bgcolor="blue" @endif>{{$good->serial}}
                            <input type="hidden" name="natures[]" value="{{$good->id}}"></td>
                            <td>{{$good->detail}}</td>
                            @foreach($team->members->sortBy('pin') as $member)
                            <input type="hidden" name="team{{$team->id}}members[]" value="{{$member->id}}">
                            <td class="form-group">  
                            <select class="form-control" name="team{{$team->id}}member{{$member->id}}natures[]">
                                <option value="0"></option>
                                <option value="1" @foreach($oldNatures as $oldNature)
                                                            @if($oldNature->member_id == $member->id && $oldNature->nature_id == $good->id && $oldNature->nature_point == '1') selected @endif
                                                        @endforeach>১</option>
                                <option value="2" @foreach($oldNatures as $oldNature)
                                                            @if($oldNature->member_id == $member->id && $oldNature->nature_id == $good->id && $oldNature->nature_point == '2') selected @endif
                                                        @endforeach>২</option>
                                <option value="3" @foreach($oldNatures as $oldNature)
                                                            @if($oldNature->member_id == $member->id && $oldNature->nature_id == $good->id && $oldNature->nature_point == '3') selected @endif
                                                        @endforeach>৩</option>
                            </select>
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br><br>
            @endforeach
            <div class="form-group">
                <div class="col-md-12">
                    {{-- <button type="submit" class="btn btn-primary pull-left">Save</button> --}}
                    <button type="button" class="btn btn-primary pull-right next">Next</button>
                </div>
            </div>
        </div>
        <div class="panel-body Next table-responsive">
            @foreach($teams as $team)
                <input type="hidden" name="teams[]" value="{{$team->id}}">
                @if($team->coordinator_id == null || $team->coordinator_id == '')
                    <div class="evaTeamHeader"> {{$team->name}} (নম্বর ভিত্তিক মূল্যায়ন)</div>
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr>
                            <th>Pin</th>
                            <th>Name</th>
                            <th>Achieved Number(without Co-ordinator)</th>
                        </tr>
                        @foreach($team->members->sortBy('pin') as $member)
                            <tr>
                                <td>{{$member->pin}}</td>
                                <td>{{$member->name}}</td>
                                <td><input type="text" name="team{{$team->id}}member{{$member->id}}markwoc" 
                                @foreach($oldMarks as $oldMark)
                                    @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                        value="{{$oldMark->mark_without_coordinator}}"
                                    @endif
                                @endforeach
                                ></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">অবশিষ্ট নম্বর</td>
                            <td>{{$team->members->count()*10}}</td>
                            
                        </tr>
                        </tbody>
                    </table>
                @else
                    <div class="evaTeamHeader"> {{$team->name}} (নম্বর ভিত্তিক মূল্যায়ন)</div>
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <tr>
                            <th>Pin</th>
                            <th>Name</th>
                            <th>Achieved Number(with Co-ordinator)</th>
                            <th>Achieved Number(without Co-ordinator)</th>
                        </tr>
                        @foreach($team->members->sortBy('pin') as $member)
                            <tr>
                                <td>{{$member->pin}}</td>
                                <td>{{$member->name}}</td>
                                <td><input type="text" name="team{{$team->id}}member{{$member->id}}markwc"
                                @foreach($oldMarks as $oldMark)
                                    @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                        value="{{$oldMark->mark_with_coordinator}}"
                                    @endif
                                @endforeach></td>
                                <td>@if($member->id !== $team->coordinator_id)
                                <input type="text" name="team{{$team->id}}member{{$member->id}}markwoc"
                                @foreach($oldMarks as $oldMark)
                                    @if($team->id == $oldMark->evaluation_team_id && $oldMark->member_id == $member->id)
                                        value="{{$oldMark->mark_without_coordinator}}"
                                    @endif
                                @endforeach>
                                @else 
                                প্রযোজ্য নয়
                                @endif</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">মোট নম্বর</td>
                            <td>{{$team->members->count()*10}}</td>
                            <td>{{($team->members->count()-1)*10}}</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                @endforeach
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                    <button type="button" class="btn btn-primary pull-left prev">Prev</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".Next").hide();
        $("button.next").click(function(){
            $(".Next").show();
            $(".Prev").hide();
        });
        $("button.prev").click(function(){
            $(".Prev").show();
            $(".Next").hide();
        });
        /*var members = [];
        $.ajax({
            url: "{{ url('/allmembers') }}",
            dataType: "json",
            async: false,
            success: function( data ) {
                members = data;
            }
        });*/
        /*$( "input.addMember" ).autocomplete({
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
        });*/
    });
</script>
@endsection
