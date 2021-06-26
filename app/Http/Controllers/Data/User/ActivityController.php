<?php
namespace App\Http\Controllers\Data\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ActivityController extends Controller
{
    public function upcomingActivities()
    {
        $records = DB::table('activity')
        ->select('*')
        ->orderByRaw('title asc')
        ->where('date','>=', DB::raw('Date(NOW())'))
        ->orderByRaw('activity.date desc ')
        ->orderByRaw('activity.time desc ')
        ->get();

        return  compact('records');
    }

    public function mainIndex()
    {
        $records = DB::table('main_activity')
        ->select('*')
        ->orderByRaw('title asc')
        ->get();

        return  compact('records');
    }
    
    
    public function mainStore(Request $request)
    {    
        
        DB::table('main_activity')->insert(
            [
            'title' => $request->title,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

     public function mainUpdate(Request $request)
    {    
        
        DB::table('main_activity')
        ->where('id','=',$request->id)
        ->update(
            [
            'title' => $request->title,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

    public function mainEdit($id)
    {    
        
         $record = DB::table('main_activity')
        ->select('*')
        ->where('id','=',$id)
        ->first();

        return  compact('record');
    }

    public function maindelete($id)
    {
        DB::table('main_activity')->where('id', '=', $id)->delete();

        return 'success';
    }

    public function index($id, $tribe_id)
    {
        $record = DB::table('main_activity')
        ->select('*')
        ->where('id','=',$id)
        ->first();

        $records = DB::table('activity')
        ->select(
            'activity.*', 
            DB::Raw('(select count(*) from attendees left join members on attendees.member_id = members.id 
            where attendees.activity_id = activity.id and members.tribe = '.$tribe_id.'
            ) as attendee')
            )
        ->orderByRaw('activity.date desc ')
        ->orderByRaw('activity.time desc ')
        ->where('activity.main_acitivity_id','=',$id)
        ->get();

        return  compact('records','record');
    }

    public function store(Request $request)
    {    
        DB::table('activity')->insert(
            [
                'main_acitivity_id' => $request->main_acitivity_id,
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

    public function update(Request $request)
    {
      DB::table('activity')
      ->where('id','=',$request->id)
      ->update(
            [
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

    

    public function delete($id)
    {
        DB::table('activity')->where('id', '=', $id)->delete();

        return 'success';
    }

    public function profile($id,$tribe_id)
    {
        $user = DB::table('activity')
        ->select('*')
        ->where('id','=',$id)
        ->orderByRaw('title asc')
        ->first();

         $records = DB::table('attendees')
            ->leftJoin('members as mb', 'attendees.member_id', '=', 'mb.id')
            ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
            ->select('mb.*'
            ,'attendees.activity_id'
            ,'attendees.member_id'
            ,'tribes.tribe_name'
            ,DB::raw('(select " ") as category_name')
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
       
            )
            ->where('attendees.activity_id','=', $id)
            ->where('mb.tribe','=', $tribe_id)
            ->orderByRaw('mb.last_name asc')
            ->get();
        
        // $records = DB::table('members as mb')
        
        // ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        // ->select('mb.*'
        //     ,'tribes.tribe_name'
        //     ,DB::raw('(select " ") as category_name')
        //     ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
        //     ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        // )
        // ->orderByRaw('last_name asc')
        // ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('user','records','categories');
    }

    public function storeAttendees(Request $request)
    {   
        $attendees = explode (",", $request->member_id); 

        foreach ($attendees as $key => $value){

            $exist = DB::table('attendees')
            ->where('activity_id','=',$request->activity_id)
            ->where('member_id','=',$value)
            ->count();

            if($exist == 0){
                DB::table('attendees')->insert(
                    [
                    'activity_id' => $request->activity_id,
                    'member_id' => $value
                    ]
                );
            }
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