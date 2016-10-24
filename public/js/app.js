$(document).ready(function(){
    $('div.evaTeamHeader').click(function(){
        $(this).next('div.evaTeamBody').slideToggle();
    });
    $('div.evaTeamHeader button.editTeam').click(function(e){
        $(this).hide();
        $(this).siblings('input').removeAttr('readonly').css('cursor', 'text !important');
        $(this).siblings('input').click(function(e){e.stopPropagation()});
        $(this).next('button.editDone').show();
        $(this).closest('div.evaTeam').find('li[style]').slideDown();
        $(this).closest('div.evaTeam').find('li label[style],li button[style]').show(500);
        e.stopPropagation();
    });
    $('div.evaTeamHeader button.editDone').click(function(e){
        $(this).hide();
        $(this).siblings('input').prop('readonly', true).removeAttr('style');
        $(this).prev('button.editTeam').show();
        $(this).closest('div.evaTeam').find('li:first, li:last').slideUp();
        $(this).closest('div.evaTeam').find('li > label,li > button').hide(500);
        e.stopPropagation();
    });
    $(document).on('click', 'div.evaTeamBody button.btnDelete', function(){
        $(this).closest('li').remove();
    });
    $(document).on('click', 'div.evaTeamBody input[type=radio]', function(){
        $(this).closest('li').siblings().removeAttr('co');
        $(this).closest('li').attr('co','');
    });
    $('div.evaTeamBody button.btnAdd').click(function(){
        if(!checkDuplicate($(this).closest('li').attr('memberid'), $(this).closest('ul'))){
            var html =
                '<li memberid="'+$(this).closest('li').attr('memberid')+'">'+
                '<label><input type="radio" name="'+$(this).closest('ul').find('input[type=radio]').attr('name')+'" value="'+$(this).closest('li').attr('memberid')+'"> co </label>'+
                '<span>'+$(this).closest('li').find('input.pin').val()+'</span>'+$(this).closest('li').find('input.name').val()+
                '<button class="btn btnDelete pull-right" type="button"><i class="fa fa-minus-circle"></i></button>'+
                '<input type="hidden" value="'+$(this).closest('li').attr('memberid')+'" name="team'+$(this).closest('div.evaTeam').attr('teamid')+'members[]">'+
                '</li>';
            $(html).insertBefore($(this).closest('ul').children('li:last'));
        }
    });
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
    $('button.btnAddNature.good').click(function(){
        $(this).closest('div.panel').find('ol.goodList').append('<li><input type="text" class="form-control" name="goods[]"></li>');
        $(this).closest('div.form-group').find('ol.badList').attr('start',$('ol.goodList').children('li').length+1);
    });
    $('button.btnAddNature.bad').click(function(){
        $(this).closest('div.panel').find('ol.badList').append('<li><input type="text" class="form-control" name="bads[]"></li>');
    });
    $('button.btnRemoveNature.bad').click(function(){
        $(this).closest('div.panel').find('ol.badList li:last-child').remove();
    });
    $('button.btnRemoveNature.good').click(function(){
        $(this).closest('div.panel').find('ol.goodList li:last-child').remove();
        $(this).closest('div.form-group').find('ol.badList').attr('start',$('ol.goodList').children('li').length+1);
    });

    //upload members by csv file
    $('button#uploadMembers').click(function () {
        csvToTable($('input#csvMembers')[0].files[0], $('#membersTable'));
        $('#membersTableBtn').html('<button class="btn btn-primary" type="button">Create</button>');
        $('#membersTableBtn button').click(function () {
            $('#membersTable tbody > tr').each(function () {
                var formData = new FormData();
                formData.append("pin", $(this).find('td:nth-child(1) input').val());
                formData.append("name", $(this).find('td:nth-child(2) input').val());
                formData.append("email", $(this).find('td:nth-child(3) input').val());
                formData.append("phone", $(this).find('td:nth-child(4) input').val());
                formData.append("joining_date", $(this).find('td:nth-child(5) input').val().trim());
                //formData.append("team", $(this).find('td:nth-child(6) input').val());
                formData.append("_token", $('input[name="_token"]').val());
                var request = new XMLHttpRequest();
                request.open("POST", window.location.origin+"/saveuserandmember");
                request.send(formData);
                $(this).find('td:nth-child(6)').text('Sending');
                var ele = $(this);
                request.onload = function () {
                    ele.find('td:nth-child(6)').text(request.responseText);
                };
            });
        });
    });

    //function to change csv to table for add member
    function csvToTable (file, ele) {
        var data = "";
        var reader = new FileReader();
        reader.readAsBinaryString(file.slice(0, file.size));
        reader.onloadend = function(evt) {
            if (evt.target.readyState == FileReader.DONE) {
                data = evt.target.result;
                var allRows = data.split(/\r?\n|\r/);
                var table = '<table class="table table-bordered table-hover">';
                for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                    if (singleRow === 0) {
                        table += '<thead>';
                        table += '<tr>';
                    } else {
                        table += '<tr>';
                    }
                    var rowCells = allRows[singleRow].split(',');
                    for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                        if (singleRow === 0) {
                            table += '<th>';
                            table += rowCells[rowCell];
                            table += '</th>';
                        } else {
                            table += '<td>';
                            table += '<input type="text" value="'+rowCells[rowCell]+'">';
                            table += '</td>';
                        }
                    }
                    if (singleRow === 0) {
                        table += '<th>Status</th></tr>';
                        table += '</thead>';
                        table += '<tbody>';
                    } else {
                        table += '<td>Pending</td></tr>';
                    }
                }
                table += '</tbody>';
                table += '</table>';
                ele.html(table);
                $('tr:last').remove();
            }
        };
    }

    //upload all attendences of members by csv file
    $('button#uploadMembersAttendences').click(function () {
        csvToTable($('input#csvMembersAttendences')[0].files[0], $('#membersAttendencesTable'));
        $('#membersAttendencesTableBtn').html('<button class="btn btn-primary" type="button">Create</button>');
        $('#membersAttendencesTableBtn button').click(function () {
            $('#membersAttendencesTable tbody > tr').each(function () {
                var formData = new FormData();
                formData.append("pin", $(this).find('td:nth-child(1) input').val());
                formData.append("total_month", $(this).find('td:nth-child(2) input').val());
                formData.append("perfect_zone", $(this).find('td:nth-child(3) input').val());
                formData.append("good_zone", $(this).find('td:nth-child(4) input').val());
                formData.append("total_mark", $(this).find('td:nth-child(5) input').val().trim());
                //formData.append("team", $(this).find('td:nth-child(6) input').val());
                formData.append("_token", $('input[name="_token"]').val());
                var request = new XMLHttpRequest();
                request.open("POST", window.location.origin+"/saveallattendences");
                request.send(formData);
                $(this).find('td:nth-child(6)').text('Sending');
                var ele = $(this);
                request.onload = function () {
                    ele.find('td:nth-child(6)').text(request.responseText);
                };
            });
        });
    });

    //function to change csv to table for add member's attendence
    function csvToTable (file, ele) {
        var data = "";
        var reader = new FileReader();
        reader.readAsBinaryString(file.slice(0, file.size));
        reader.onloadend = function(evt) {
            if (evt.target.readyState == FileReader.DONE) {
                data = evt.target.result;
                var allRows = data.split(/\r?\n|\r/);
                var table = '<table class="table table-bordered table-hover attendence">';
                for (var singleRow = 0; singleRow < allRows.length; singleRow++) {
                    if (singleRow === 0) {
                        table += '<thead>';
                        table += '<tr>';
                    } else {
                        table += '<tr>';
                    }
                    var rowCells = allRows[singleRow].split(',');
                    for (var rowCell = 0; rowCell < rowCells.length; rowCell++) {
                        if (singleRow === 0) {
                            table += '<th>';
                            table += rowCells[rowCell];
                            table += '</th>';
                        } else {
                            table += '<td>';
                            table += '<input type="text" value="'+rowCells[rowCell]+'">';
                            table += '</td>';
                        }
                    }
                    if (singleRow === 0) {
                        table += '<th>Status</th></tr>';
                        table += '</thead>';
                        table += '<tbody>';
                    } else {
                        table += '<td>Pending</td></tr>';
                    }
                }
                table += '</tbody>';
                table += '</table>';
                ele.html(table);
                $('table.attendence tr:last').remove();
            }
        };
    }

});
