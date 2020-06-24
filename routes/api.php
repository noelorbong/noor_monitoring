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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');

        
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
    });


});

Route::get('activity/upcoming', 'Data\ActivityController@upcomingActivities');
Route::get('member/new/count/{days}', 'Data\MemberController@newMemberCount');
Route::get('member/new/{days}', 'Data\MemberController@newMember');

// Route::get('options', 'Data\MemberController@options');
// Route::get('member/list', 'Data\MemberController@index');
// Route::get('activity/list/{id}', 'Data\ActivityController@index');
// Route::get('tribe/list', 'Data\TribeController@index');
// Route::get('tribe/edit/{id}', 'Data\TribeController@edit');
// Route::get('tribe/profile/{id}', 'Data\TribeController@profile');
// Route::get('category/profile/{id}', 'Data\CategoryController@profile');
// Route::get('member/profile/{id}', 'Data\MemberController@profile');
// Route::get('member/flock/{id}', 'Data\MemberController@flock');

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});
