<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('login', 'AuthController@login');
Route::get('register', 'AuthController@register');
Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');

        //Admin
        Route::get('options', 'Data\MemberController@options');
        Route::post('member/store', 'Data\MemberController@store');
        Route::post('member/update', 'Data\MemberController@update');
        Route::get('member/list', 'Data\MemberController@index');
        Route::get('member/profile/{id}', 'Data\MemberController@profile');
        Route::delete('member/delete/{id}', 'Data\MemberController@delete');
        Route::get('member/report/{id}', 'Data\MemberController@report');
        Route::get('member/flock/{id}', 'Data\MemberController@flock');
        Route::post('member/mentor/update', 'Data\MemberController@mentorUpdate');
        Route::delete('member/flock/remove/{id}', 'Data\MemberController@flockRemove');
        Route::get('member/new/count/{days}', 'Data\MemberController@newMemberCount');
        Route::get('member/new/{days}', 'Data\MemberController@newMember');

        Route::post('activity/main/store', 'Data\ActivityController@mainStore');
        Route::post('activity/main/update', 'Data\ActivityController@mainUpdate');
        Route::get('activity/main/list', 'Data\ActivityController@mainIndex');
        Route::get('activity/main/edit/{id}', 'Data\ActivityController@mainEdit');
        Route::delete('activity/main/delete/{id}', 'Data\ActivityController@maindelete');

        Route::post('activity/store', 'Data\ActivityController@store');
        Route::post('activity/update', 'Data\ActivityController@update');
        Route::get('activity/list/{id}', 'Data\ActivityController@index');
        Route::get('activity/profile/{id}', 'Data\ActivityController@profile');
        Route::delete('activity/delete/{id}', 'Data\ActivityController@delete');
        Route::post('activity/storeattendees', 'Data\ActivityController@storeAttendees');
        Route::delete('activity/deleteattendee/{member_id}/{activity_id}', 'Data\ActivityController@deleteAttendee');
        Route::get('activity/upcoming', 'Data\ActivityController@upcomingActivities');

        Route::get('tribe/list', 'Data\TribeController@index');
        Route::post('tribe/store', 'Data\TribeController@store');
        Route::post('tribe/update', 'Data\TribeController@update');
        Route::get('tribe/edit/{id}', 'Data\TribeController@edit');
        Route::delete('tribe/delete/{id}', 'Data\TribeController@delete');
        Route::get('tribe/profile/{id}', 'Data\TribeController@profile');
        Route::post('tribe/member/update', 'Data\TribeController@memberUpdate');
        Route::delete('tribe/remove/{id}', 'Data\TribeController@remove');


        Route::get('category/list', 'Data\CategoryController@index');
        Route::post('category/store', 'Data\CategoryController@store');
        Route::post('category/update', 'Data\CategoryController@update');
        Route::get('category/edit/{id}', 'Data\CategoryController@edit');
        Route::delete('category/delete/{id}', 'Data\CategoryController@delete');
        Route::get('category/profile/{id}', 'Data\CategoryController@profile');
        Route::post('category/member/update', 'Data\CategoryController@memberUpdate');
        Route::delete('category/remove/{id}/{category_id}', 'Data\CategoryController@remove');


        Route::get('specializedministry/list', 'Data\SpecializedMinistryController@index');
        Route::post('specializedministry/store', 'Data\SpecializedMinistryController@store');
        Route::post('specializedministry/update', 'Data\SpecializedMinistryController@update');
        Route::get('specializedministry/edit/{id}', 'Data\SpecializedMinistryController@edit');
        Route::delete('specializedministry/delete/{id}', 'Data\SpecializedMinistryController@delete');
        Route::get('specializedministry/profile/{id}', 'Data\SpecializedMinistryController@profile');
        Route::post('specializedministry/member/update', 'Data\SpecializedMinistryController@memberUpdate');
        Route::delete('specializedministry/remove/{id}/{specializedministry_id}', 'Data\SpecializedMinistryController@remove');


        Route::get('lifeclass/list', 'Data\LifeclassController@index');
        Route::post('lifeclass/store', 'Data\LifeclassController@store');
        Route::post('lifeclass/update', 'Data\LifeclassController@update');
        Route::get('lifeclass/edit/{id}', 'Data\LifeclassController@edit');
        Route::delete('lifeclass/delete/{id}', 'Data\LifeclassController@delete');
        Route::get('lifeclass/profile/{id}', 'Data\LifeclassController@profile');
        Route::post('lifeclass/member/update', 'Data\LifeclassController@memberUpdate');
        Route::delete('lifeclass/remove/{id}/{lifeclass_id}', 'Data\LifeclassController@remove');
        

        Route::get('progress/list', 'Data\ProgressController@index');
        Route::post('progress/store', 'Data\ProgressController@store');
        Route::post('progress/update', 'Data\ProgressController@update');
        Route::get('progress/edit/{id}', 'Data\ProgressController@edit');
        Route::delete('progress/delete/{id}', 'Data\ProgressController@delete');
        Route::get('progress/profile/{id}', 'Data\ProgressController@profile');
        Route::post('progress/member/update', 'Data\ProgressController@memberUpdate');
        Route::delete('progress/remove/{id}/{progress_id}', 'Data\ProgressController@remove');
        //admin end

        //User
        Route::get('{tribe_id}/member/list', 'Data\User\MemberController@index');
        Route::get('user/options', 'Data\User\MemberController@options');
        Route::post('user/member/store', 'Data\User\MemberController@store');
        Route::post('user/member/update', 'Data\User\MemberController@update');
        Route::get('user/member/profile/{id}', 'Data\User\MemberController@profile');
        Route::delete('user/member/delete/{id}', 'Data\User\MemberController@delete');
        Route::get('user/member/report/{id}', 'Data\User\MemberController@report');
        Route::get('user/member/flock/{id}', 'Data\User\MemberController@flock');
        Route::post('user/member/mentor/update', 'Data\User\MemberController@mentorUpdate');
        Route::delete('user/member/flock/remove/{id}', 'Data\User\MemberController@flockRemove');
        Route::get('user/member/new/count/{days}/{tribe_id}', 'Data\User\MemberController@newMemberCount');
        Route::get('user/member/new/{days}/{tribe_id}', 'Data\User\MemberController@newMember');

        Route::get('user/progress/list/{tribe_id}', 'Data\User\ProgressController@index');
        Route::post('user/progress/store', 'Data\User\ProgressController@store');
        Route::post('user/progress/update', 'Data\User\ProgressController@update');
        Route::get('user/progress/edit/{id}', 'Data\User\ProgressController@edit');
        Route::delete('user/progress/delete/{id}', 'Data\User\ProgressController@delete');
        Route::get('user/progress/profile/{tribe_id}/{id}', 'Data\User\ProgressController@profile');
        Route::post('user/progress/member/update', 'Data\User\ProgressController@memberUpdate');
        Route::delete('user/progress/remove/{id}/{progress_id}', 'Data\User\ProgressController@remove');

        Route::get('user/lifeclass/list/{tribe_id}', 'Data\User\LifeclassController@index');
        Route::post('user/lifeclass/store', 'Data\User\LifeclassController@store');
        Route::post('user/lifeclass/update', 'Data\User\LifeclassController@update');
        Route::get('user/lifeclass/edit/{id}', 'Data\User\LifeclassController@edit');
        Route::delete('user/lifeclass/delete/{id}', 'Data\User\LifeclassController@delete');
        Route::get('user/lifeclass/profile/{tribe_id}/{id}', 'Data\User\LifeclassController@profile');
        Route::post('user/lifeclass/member/update', 'Data\User\LifeclassController@memberUpdate');
        Route::delete('user/lifeclass/remove/{id}/{lifeclass_id}', 'Data\User\LifeclassController@remove');

        Route::get('user/activity/list/{id}/{tribe_id}', 'Data\User\ActivityController@index');
        Route::get('user/activity/profile/{id}/{tribe_id}', 'Data\User\ActivityController@profile');

        Route::post('tribeactivity/main/store', 'Data\User\TribeactivityController@mainStore');
        Route::post('tribeactivity/main/update', 'Data\User\TribeactivityController@mainUpdate');
        Route::get('tribeactivity/main/list/{tribe_id}', 'Data\User\TribeactivityController@mainIndex');
        Route::get('tribeactivity/main/edit/{id}', 'Data\User\TribeactivityController@mainEdit');
        Route::delete('tribeactivity/main/delete/{id}', 'Data\User\TribeactivityController@maindelete');

        Route::post('tribeactivity/store', 'Data\User\TribeactivityController@store');
        Route::post('tribeactivity/update', 'Data\User\TribeactivityController@update');
        Route::get('tribeactivity/list/{id}', 'Data\User\TribeactivityController@index');
        Route::get('tribeactivity/profile/{id}', 'Data\User\TribeactivityController@profile');
        Route::delete('tribeactivity/delete/{id}', 'Data\User\TribeactivityController@delete');
        Route::post('tribeactivity/storeattendees', 'Data\User\TribeactivityController@storeAttendees');
        Route::delete('tribeactivity/deleteattendee/{member_id}/{activity_id}', 'Data\User\TribeactivityController@deleteAttendee');
        Route::get('tribeactivity/upcoming/{tribe_id}', 'Data\User\TribeactivityController@upcomingActivities');
    });


});

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});
