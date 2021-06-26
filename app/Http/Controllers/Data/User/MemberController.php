<?php
namespace App\Http\Controllers\Data\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MemberController extends Controller
{
    public function newMemberCount($day,$tribe_id)
    {
        $records = DB::table('members')
        ->select(DB::raw('count(*) as count')  )
        ->where('spiritual_dob', '>', DB::raw('DATE_ADD(Date(NOW()), INTERVAL -'.$day.' DAY)'))
        ->where('tribe','=',$tribe_id)
        ->first();

        return  compact('records');
    }

     public function newMember($day, $tribe_id)
    {
        $records = DB::table('members as mb')
        
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        // ->leftJoin('categories', 'mb.category', 'like', db::raw('concat("%",categories.id,"%")'))
        ->select('mb.*'
            // ,'tribes.tribe_name'
            ,DB::raw('(select " ") as category_name')
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
        ->orderByRaw('last_name asc')
        ->where('spiritual_dob', '>', DB::raw('DATE_ADD(Date(NOW()), INTERVAL -'.$day.' DAY)'))
        ->where('mb.tribe','=',$tribe_id)
        ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('records','categories');
    }

    public function index($tribe_id)
    {
        $records = DB::table('members as mb')
        
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        ->leftJoin('progresses', 'mb.progress', '=', 'progresses.id')
        // ->leftJoin('categories', 'mb.category', 'like', db::raw('concat("%",categories.id,"%")'))
        ->select('mb.*'
            ,'tribes.tribe_name'
            ,'progresses.progress_name'
            ,DB::raw('(select " ") as category_name')
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
        ->where('mb.tribe','=',$tribe_id)
        ->orderByRaw('last_name asc')
        ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('records','categories');
    }

    public function options()
    {
        $tribes = DB::table('tribes')
        ->select('*')
        ->orderByRaw('tribe_name asc')
        ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('tribes','categories');
    }
    
    public function store(Request $request)
    {
        $category = $request->category;
        if($request->category == null || $request->category == ""){
            $category = "1";
        }
        // return $request;
        $photo = "image_icon";
        if($request->file('photo')){
            $photo = str_random(25);
            $cover = $request->file('photo');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put( $photo.'.png',  File::get($cover));
        }

        // return $request;
        
        DB::table('members')->insert(
            [
            'photo' =>  $photo.'.png',
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'name_extension' => $request->name_extension,
            'middle_name' => $request->middle_name,
            'email_address' => $request->email_address,
            'contact_number' => $request->contact_number,
            'dob' => $request->dob,
            'spiritual_dob' => $request->spiritual_dob == null? DB::RAW('Date(NOW())'):$request->spiritual_dob,
            'gender' => $request->gender,
            'category' => $category,
            'tribe' => $request->tribe,
            'mentor' => $request->mentor,
            'specializedministry' => $specializedministry,
            'progress' => $request->progress,
            'lifeclass' => $request->lifeclass,
            'label' => $request->label,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'zipcode' => $request->zipcode
            ]
        );
        
        return 'success';
    }

    public function update(Request $request)
    {
      // return $request;
        $category = $request->category;
        if($request->category == null || $request->category == ""){
            $category = "1";
        }

        $photo = "image_icon";
         if($request->photo_name != "image_icon.png"){
            $photo = str_replace(".png","",$request->photo_name);
         }

        if($request->file('photo')){
            if($request->photo_name != "image_icon.png"){
                 if(Storage::disk('public')->exists($request->photo_name)){
                Storage::disk('public')->delete($request->photo_name);
              }else{
                // dd('File does not exists.');
              }
        }

        $photo = str_random(25);
        $cover = $request->file('photo');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put( $photo.'.png',  File::get($cover));
        }
        
        DB::table('members')
        ->where('id','=',$request->id)
        ->update(
            [
            'photo' =>  $photo.'.png',
            'last_name' => $request->last_name == "null"? "":$request->last_name,
            'first_name' => $request->first_name == "null"? "":$request->first_name,
            'name_extension' => $request->name_extension == "null"? "":$request->name_extension,
            'middle_name' => $request->middle_name == "null"? "":$request->middle_name,
            'email_address' => $request->email_address == "null"? "":$request->email_address,
            'contact_number' => $request->contact_number == "null"? "":$request->contact_number,
            'dob' => $request->dob == "null"? null:$request->dob,
            'spiritual_dob' => $request->spiritual_dob == "null"? null:$request->spiritual_dob,
            'gender' => $request->gender,
            'category' =>  $category,
            'tribe' => (int)$request->tribe,
            'mentor' => (int)$request->mentor,
            'label' => (int)$request->label,

            'address1' => $request->address1 == "null"? "":$request->address1,
            'address2' => $request->address2 == "null"? "":$request->address2,
            'barangay' => $request->barangay == "null"? "":$request->barangay,
            'municipality' => $request->municipality == "null"? "":$request->municipality,
            'province' => $request->province == "null"? "":$request->province,
            'zipcode' => $request->zipcode == "null"? "":$request->zipcode
            ]
        );
        
        return 'success';
    }

    public function delete($id)
    {
        DB::table('members')->where('id', '=', $id)->delete();

        return 'success';
    }

    public function profile($id)
    {
        $user = DB::table('members as mb')
        
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        
        //  ->leftJoin('specializedministries', 'mb.specializedministry', '=', 'specializedministries.id')
        
        ->leftJoin('lifeclasses', 'mb.lifeclass', '=', 'lifeclasses.id')
        ->leftJoin('progresses', 'mb.progress', '=', 'progresses.id')
        ->select('mb.*'
            ,DB::raw('(tribes.id) as tribe_id')
            ,'lifeclasses.lifeclass_name'
            // ,'specializedministries.specializedministry_name'
            ,'progresses.progress_name'
            ,'tribes.tribe_name'
            ,'tribes.tribe_leader'
            
            ,DB::raw('(select " ") as category_name')
            
            ,DB::raw('(select " ") as specializedministry_name')

            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = tribes.tribe_leader limit 1) as tribe_leader_name')
        )
        ->where('mb.id','=',$id)
        ->orderByRaw('mb.last_name asc')
        ->first();

         $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        // return  compact('user','categories');


        $specializedministries = DB::table('specializedministries')
        ->select('id as value','specializedministry_name as text')
        ->orderByRaw('specializedministry_name asc')
        ->get();

        return  compact('user','specializedministries','categories');
    }

    public function report($id)
    {
         $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        $records = DB::table('attendees')
            ->leftJoin('activity', 'attendees.activity_id', '=', 'activity.id')
            ->select('activity.*')
            ->where('attendees.member_id','=', $id)
            ->orderByRaw('activity.date desc')
            ->get();

        return  compact('records','categories');
    }

    public function flock($id)
    {
         
        $flocks = DB::table('members as mb')
        
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        // ->leftJoin('categories', 'mb.category', 'like', db::raw('concat("%",categories.id,"%")'))
        ->select('mb.*'
            ,'tribes.tribe_name'
            ,DB::raw('(select " ") as category_name')
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
        ->where('mb.mentor','=',$id)
        ->orderByRaw('last_name asc')
        ->get();

        $categories = DB::table('categories')
        ->select('id as value','category_name as text')
        ->orderByRaw('category_name asc')
        ->get();

        return  compact('categories','flocks');
    }

    
    public function flockRemove($id){
            DB::table('members')
                    ->where('id','=',$id)
                    ->update(
                        [
                        'mentor' => 0,
                        ]
            );

            return 'success';
    }
    public function mentorUpdate(Request $request)
        {   
            $members = explode (",", $request->member_id); 

            foreach ($members as $key => $value){

                DB::table('members')
                    ->where('id','=',$value)
                    ->update(
                        [
                        'mentor' => $request->mentor_id,
                        ]
                    );

            }
            
            return 'success';
        }
}