<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TribeController extends Controller
{
      
    public function index()
    {
        $records = DB::table('tribes as tr')
        ->select(
            'tr.*'
            ,DB::Raw('(select count(*) from members where members.tribe = tr.id) as tribe_member')
             ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = tr.tribe_leader limit 1) as tribe_leader_name')
        
            //, DB::Raw('(select count(*) from attendees where attendees.activity_id = activity.id) as attendee')
            )
        ->orderByRaw('tr.tribe_name asc')
        ->get();

        return  compact('records');
    }

    public function store(Request $request)
    {    
        $default = DB::table('tribes')
        ->select(
            DB::RAW('Count(*) as counted')     
           )
        ->where('id','=',1)
        ->first();

        if($default->counted <= 0){
            DB::table('tribes')->insert(
                [
                'id' => 1,
                'tribe_name' => ' No Tribe',
                'tribe_leader' => 0,
            'username' => 'None',
            'password' => 'None',
            'description' => 'No Tribe'
                ]
            );
        }

        
        DB::table('tribes')->insert(
            [
            'tribe_name' => $request->tribe_name,
            'tribe_leader' => $request->tribe_leader,
            'username' => $request->username,
            'password' => $request->password,
            'description' => $request->description
            ]
        );

        $record = DB::table('tribes')
        ->select(
            'tribes.*'      
           )
        ->where('tribes.username','=',$request->username)
        ->first();

     DB::table('users')
      ->where('email','=',$request->username)
      ->update(
            [
            'tribe' => $record->id,
            'updated_at' => DB::Raw("NOW()")
            ]
        );
        
        return 'success';
    }

     public function edit($id)
    {    
        
         $record = DB::table('tribes as tr')
        ->select(
            'tr.*'
             ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = tr.tribe_leader limit 1) as tribe_leader_name')
                    
           )
        ->where('tr.id','=',$id)
        ->first();

        return  compact('record');
    }

    public function update(Request $request)
    {

        $user = DB::table('tribes')
        ->select(DB::raw('(count(*)) as number_count')
           )
        ->where('username','=',$request->username)
        ->where('id','!=',$request->id)
        ->first();

        if($user->number_count > 0){
            return 'failed';
        }

      DB::table('tribes')
      ->where('id','=',$request->id)
      ->update(
            [
            'tribe_name' => $request->tribe_name,
            'tribe_leader' => $request->tribe_leader,
            'username' => $request->username,
            'password' => $request->password,
            'description' => $request->description,
            'updated_at' => DB::Raw("NOW()")
            ]
        );

    DB::table('users')
      ->where('tribe','=',$request->id)
      ->update(
            [
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'updated_at' => DB::Raw("NOW()")
            ]
        );
        
        return 'success';
    }

    public function delete($id)
    {
        DB::table('tribes')->where('id', '=', $id)->delete();
         DB::table('users')->where('tribe', '=', $id)->delete();


        DB::table('members')
      ->where('tribe','=',$id)
      ->update(
            [
            'tribe' => 1,
            'updated_at' => DB::Raw("NOW()")
            ]
        );

        return 'success';
    }

    public function remove($id)
    {
        DB::table('members')
      ->where('id','=',$id)
      ->update(
            [
            'tribe' => 1,
            'updated_at' => DB::Raw("NOW()")
            ]
        );

        return 'success';
    }

    public function profile($id)
    {
        $user = DB::table('tribes as tr')
        ->select(
            'tr.*'
             ,DB::Raw('(select count(*) from members where members.tribe = tr.id) as tribe_member')
             ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = tr.tribe_leader limit 1) as tribe_leader_name')
           )
        ->where('id','=',$id)
        ->orderByRaw('tr.tribe_name asc')
        ->first();

         $records = DB::table('members as mb')
        
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        ->leftJoin('categories', 'mb.category', '=', 'categories.id')
        ->select('mb.*'
        ,'tribes.tribe_name'
        ,'categories.category_name'
        ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
        ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')  
        ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
            ->where('mb.tribe','=', $id)
            ->orderByRaw('mb.last_name asc')
            ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('user','records','categories');
    }

    public function memberUpdate(Request $request)
    {   
        $members = explode (",", $request->member_id); 

        foreach ($members as $key => $value){

             DB::table('members')
                ->where('id','=',$value)
                ->update(
                    [
                    'tribe' => $request->tribe_id,
                    ]
                );

        }
        
        return 'success';
    }

    public function deleteAttendee($member_id,$activity_id)
    {
        DB::table('attendees')
        ->where('activity_id', '=', $activity_id)
        ->where('member_id', '=', $member_id)
        ->delete();

        return 'success';
    }

}