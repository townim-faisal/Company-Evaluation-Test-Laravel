@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading ">Add Evaluation Marks</div>
        @if($active == 1)
        @if($oldMarks == 0)
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
                <p>এই ধাপে আপনার হাতে কিছু নম্বর দেওয়া হচ্ছে। এই নম্বর আপনি নির্দিষ্ট সদস্যের (নিজেকে সহ) আচার-আচরণ, কাজের দক্ষতা, টিমে বা কোম্পানিতে তার অবদান, সহযোগিতা মূলক মনোভাব, প্রতিষ্ঠান ও কাজের প্রতি শ্রদ্ধা বোধ প্রভৃতি বিষয়গুলোকে মাথায় রেখে বণ্টন করতে পারেন। </p>
                </div>
            </div>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/saveusermarks') }}">
        {!! csrf_field() !!}
        <div class="panel-body Prev">
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
                            <td @if($good->type=="0") bgcolor="#D2D2C1" @endif>{{$good->serial}}
                            <input type="hidden" name="natures[]" value="{{$good->id}}"></td>
                            <td>{{$good->detail}}</td>
                            @foreach($team->members->sortBy('pin') as $member)
                            <input type="hidden" name="team{{$team->id}}members[]" value="{{$member->id}}">
                            <td class="form-group">  
                            <select class="form-control" name="team{{$team->id}}member{{$member->id}}natures[]">
                                <option value="0"></option>
                                <option value="1">১</option>
                                <option value="2">২</option>
                                <option value="3">৩</option>
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
        <div class="panel-body Next">
            @foreach($teams as $team)
                <input type="hidden" name="teams[]" value="{{$team->id}}">
                @if($team->coordinator_id == null || $team->coordinator_id == '')
                    <div class="evaTeamHeader"> {{$team->name}} (নম্বর ভিত্তিক মূল্যায়ন)</div>
                    <table class="table table-bordered table-hover marks">
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
                                <td><input type="text" name="team{{$team->id}}member{{$member->id}}markwoc" value=""></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">মোট নম্বর</td>
                            <td class="totalMarkWoc">{{($team->members->count())*10}}</td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    <div class="evaTeamHeader"> {{$team->name}} (নম্বর ভিত্তিক মূল্যায়ন)</div>
                    <table class="table table-bordered table-hover marks">
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
                                <td><input type="text" name="team{{$team->id}}member{{$member->id}}markwc" value=""></td>
                                <td>@if($member->id != $team->coordinator_id)
                                <input type="text" name="team{{$team->id}}member{{$member->id}}markwoc" value="">
                                @else 
                                প্রযোজ্য নয়
                                @endif</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">মোট নম্বর</td>
                            <td class="totalMarkWc">{{$team->members->count()*10}}</td>
                            <td class="totalMarkWoc">{{($team->members->count()-1)*10}}</td>
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
        @else
        You already entered the marks.
        @endif
        @else
        No active evaluation.
        @endif
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

        var totalMarkWc = []; 
        var totalMarkWoc = [];  
        
        $("table.marks").each(function(index, element){
            var x = $(element).find("td.totalMarkWc").text();
            var y = $(element).find("td.totalMarkWoc").text();
            if(x == '') {x =0};
            if(y == '') {y =0};
            totalMarkWc[index] = parseInt(x); 
            totalMarkWoc[index] = parseInt(y);
        });
            
        console.log("totalMarkWoc="+totalMarkWoc);
        console.log("totalMarkWc="+totalMarkWc);
        
        $("button[type='submit']").click(function(event){
            //event.preventDefault();
            var totalWOCUser = [];
            var totalWCUser = [];
            $("table.marks").each(function(index, element){
                totalWOCUser[index] = 0;  
                $(element).find("input[name$='markwoc']").each(function(){
                    var x = $(this).val();
                    if(x == ''){x = 0;}
                    totalWOCUser[index] += parseInt(x);
                });
            });
            
            $("table.marks").each(function(index, element){
                totalWCUser[index] = 0;  
                $(element).find("input[name$='markwc']").each(function(){
                    var x = $(this).val();
                    if(x == ''){x = 0;}
                    totalWCUser[index] += parseInt(x);
                });
            });
            
            $("table.marks").each(function(i){
                console.log("woc="+totalWOCUser);
                console.log("wc="+totalWCUser);
                if(totalWCUser[i]>totalMarkWc[i]){
                    event.preventDefault();
                    alert("Your total mark with co-ordinator is greater than total mark with co-ordinator");
                }
                if(totalWOCUser[i]>totalMarkWoc[i]){
                    event.preventDefault();
                    alert("Your total mark without co-ordinator is greater than total mark with co-ordinator");
                }
                if(totalWCUser[i]<totalMarkWc[i]){
                    event.preventDefault();
                    alert("Your total mark with co-ordinator is lower than total mark with co-ordinator");
                }
                if(totalWOCUser[i]<totalMarkWoc[i]){
                    event.preventDefault();
                    alert("Your total mark without co-ordinator is lower than total mark with co-ordinator");
                }
            });
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
