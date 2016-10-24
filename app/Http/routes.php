<?php


Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'HomeController@index');
    
    //attendence
    Route::get('/attendences', 'HrController@getAttendences');
    Route::get('/attendence', 'HrController@editAttendence');
    Route::post('/saveattendence', 'HrController@saveAttendence');
    Route::post('/saveallattendences', 'HrController@saveAllAttendences');

    Route::get('/addteam', 'HrController@addTeam');
    Route::get('/editteams', 'HrController@editTeams');
    Route::post('/saveteam', 'HrController@saveTeam');
    Route::post('/updateteams', 'HrController@updateTeams');
    Route::get('/addmember', 'HrController@addMember');
    Route::get('/memberlist', 'HrController@memberList');
    Route::post('/saveuserandmember', 'HrController@saveUserAndMember');
    Route::get('/editmember', 'HrController@editMember');
    Route::post('/savemember', 'HrController@saveMember');
    Route::post('/updatemember', 'HrController@updateMember');
    Route::get('/addevaluation', 'HrController@addEvaluation');
    Route::post('/saveevaluation', 'HrController@saveEvaluation');
    Route::get('/addmarks', 'HrController@addMarks');
    Route::post('/savemarks', 'HrController@saveMarks');
    Route::post('/saveusermarks', 'UserController@saveMarks');
    Route::get('/progress', 'HrController@showProgress');
    Route::get('/showresult', 'HrController@showResult');
    Route::get('/result/team', 'HrController@showTeamSummary');
    Route::get('/detailresult', 'HrController@detailResult');
    Route::get('/closeevaluation', 'HrController@closeEvaluation');
    Route::post('/edituser', 'SupperAdminController@editUser');
    Route::get('/allmembers', 'CommonController@getAllMembers');
    Route::get('/uploadmarks', 'HrController@uploadMarks');
    Route::get('/uploadnatures', 'HrController@uploadNatures');
});
