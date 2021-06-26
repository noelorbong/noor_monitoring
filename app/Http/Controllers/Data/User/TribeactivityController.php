<?php
namespace App\Http\Controllers\Data\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TribeactivityController extends Controller
{
    public function upcomingActivities($tribe_id)
    {
        $records1 = DB::table('tribeactivity')
        ->select('*', DB::RAW('(select 2) as main'))
        ->orderByRaw('title asc')
        ->where('date','>=', DB::raw('Date(NOW())'))
        ->where('tribe_id','=',$tribe_id)
        ->orderByRaw('tribeactivity.date asc ')
        ->orderByRaw('tribeactivity.time asc ')
        ->get();

        $record2 = DB::table('activity')
        ->select('*', DB::RAW('(select 1) as main'))
        ->orderByRaw('title asc')
        ->where('date','>=', DB::raw('Date(NOW())'))
        ->orderByRaw('activity.date desc ')
        ->orderByRaw('activity.time desc ')
        ->get();

        $records = $records1->merge($record2);

        return  compact('records');
    }

    public function mainIndex($tribe_id)
    {
        $records = DB::table('main_tribeactivity')
        ->select('*')
        ->where('tribe_id','=',$tribe_id)
        ->orderByRaw('title asc')
        ->get();

        return  compact('records');
    }
    
    
    public function mainStore(Request $request)
    {    
        
        DB::table('main_tribeactivity')->insert(
            [
            'title' => $request->title,
            'tribe_id' => $request->tribe_id,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

     public function mainUpdate(Request $request)
    {    
        
        DB::table('main_tribeactivity')
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
        
         $record = DB::table('main_tribeactivity')
        ->select('*')
        ->where('id','=',$id)
        ->first();

        return  compact('record');
    }

    public function maindelete($id)
    {
        DB::table('main_tribeactivity')->where('id', '=', $id)->delete();

        return 'success';
    }

    public function index($id)
    {
        $record = DB::table('main_tribeactivity')
        ->select('*')
        ->where('id','=',$id)
        ->first();

        $records = DB::table('tribeactivity')
        ->select(
            'tribeactivity.*', DB::Raw('(select count(*) from tribe_attendees where tribe_attendees.tribeactivity_id = tribeactivity.id) as tribe_attendee')
            )
        ->orderByRaw('tribeactivity.date desc ')
        ->orderByRaw('tribeactivity.time desc ')
        ->where('tribeactivity.main_tribeactivity_id','=',$id)
        ->get();

        return  compact('records','record');
    }

    public function store(Request $request)
    {    
        DB::table('tribeactivity')->insert(
            [
            'main_tribeactivity_id' => $request->main_tribeactivity_id,
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'tribe_id' => $request->tribe_id
            ]
        );
        
        return 'success';
    }

    public function update(Request $request)
    {
      DB::table('tribeactivity')
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
        DB::table('tribeactivity')->where('id', '=', $id)->delete();

        return 'success';
    }

    public function profile($id)
    {
        $user = DB::table('tribeactivity as tr')
        ->leftJoin('main_tribeactivity as mtr', 'tr.main_tribeactivity_id', '=', 'mtr.id')
        ->select('tr.*','mtr.title')
        ->where('tr.id','=',$id)
        ->orderByRaw('tr.title asc')
        ->first();

         $records = DB::table('tribe_attendees')
            ->leftJoin('members as mb', 'tribe_attendees.member_id', '=', 'mb.id')
            ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
            ->select('mb.*'
            ,'tribe_attendees.tribeactivity_id'
            ,'tribe_attendees.member_id'
            ,'tribes.tribe_name'
            ,DB::raw('(select " ") as category_name')
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
       
            )
            ->where('tribe_attendees.tribeactivity_id','=', $id)
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
        $tribe_attendees = explode (",", $request->member_id); 

        foreach ($tribe_attendees as $key => $value){

            $exist = DB::table('tribe_attendees')
            ->where('tribeactivity_id','=',$request->tribeactivity_id)
            ->where('member_id','=',$value)
            ->count();

            if($exist == 0){
                DB::table('tribe_attendees')->insert(
                    [
                    'tribeactivity_id' => $request->tribeactivity_id,
                    'member_id' => $value
                    ]
                );
            }
        }
        
        return 'success';
    }

    public function deleteAttendee($member_id,$tribeactivity_id)
    {
        DB::table('tribe_attendees')
        ->where('tribeactivity_id', '=', $tribeactivity_id)
        ->where('member_id', '=', $member_id)
        ->delete();

        return 'success';
    }

}