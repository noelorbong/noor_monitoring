<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProgressController extends Controller
{
      
    public function index()
    {
        $records = DB::table('progresses as ct')
        ->select(
            'ct.*'
            ,DB::Raw('(select count(*) from members where  FIND_IN_SET(ct.id,members.progress) > 0 ) as progress_member')
            
           )
        ->orderByRaw('ct.progress_name asc')
        ->get();

        return  compact('records');
    }

    public function store(Request $request)
    {    
        $default = DB::table('progresses')
        ->select(
            DB::RAW('Count(*) as counted')     
           )
        ->where('id','=',1)
        ->first();

        if($default->counted <= 0){
            DB::table('progresses')->insert(
                [
                'id' => 1,
                'progress_name' => ' No progress',
                'description' => 'No progress'
                ]
            );
        }

        DB::table('progresses')->insert(
            [
            'progress_name' => $request->progress_name,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

     public function edit($id)
    {    
        
         $record = DB::table('progresses')
        ->select(
            '*'     
           )
        ->where('id','=',$id)
        ->first();

        return  compact('record');
    }

    public function update(Request $request)
    {
        DB::table('progresses')
        ->where('id','=',$request->id)
        ->update([
            'progress_name' => $request->progress_name,
            'description' => $request->description,
            'updated_at' => DB::Raw("NOW()")
            ]
        );
        
        return 'success';
    }

    public function delete($id)
    {
    
    DB::table('progresses')->where('id', '=', $id)->delete();

    $users = DB::table('members')
            ->select(
                'members.*'
            )
            ->where(DB::raw('FIND_IN_SET('.$id.',members.progress)') , '>', 0)
            ->get();

    foreach ($users as $user){

    
        $str_arr = explode (",", $user->progress);
        $array1= array();

        foreach ($str_arr as $value){ 
            if($value != $id){
                array_push($array1,$value);
            } 
        } 

        if(count($array1) > 0){
            $new_progress = join( ',', $array1);
        }else{
            $new_progress = "1";
        }

        DB::table('members')
        ->where('id','=', $user->id)
        ->update(
                [
                'progress' => $new_progress,
                'updated_at' => DB::Raw("NOW()")
                ]
            );
    }
        return 'success';
    }

    public function remove($id,$progress_id)
    {
        $user = DB::table('members')
            ->select(
                'members.*'
            )
            ->where('id','=',$id)
            ->first();

        $str_arr = explode (",", $user->progress);
        $array1= array();

        foreach ($str_arr as $value){ 
            if($value != $progress_id){
                array_push($array1,$value);
            } 
        } 

    if(count($array1) > 0){
            $new_progress = join( ',', $array1);
        }else{
            $new_progress = "1";
        }

    DB::table('members')
      ->where('id','=',$id)
      ->update(
            [
            'progress' => $new_progress,
            'updated_at' => DB::Raw("NOW()")
            ]
        );

        return 'success';
    }

    public function profile($id)
    {
        $user = DB::table('progresses as ct')
        ->select(
            'ct.*'
             ,DB::Raw('(select count(*) from members where  FIND_IN_SET(ct.id,members.progress) > 0 ) as progress_member')
            
           )
        ->where('id','=',$id)
        ->first();

         $records = DB::table('members as mb')
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        //->leftJoin('progresses', 'mb.progress', '=', 'progresses.id')
        ->select('mb.*'
        ,'tribes.tribe_name'
        //,'progresses.progress_name'
        ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
        ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name') 
        ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
       // ->whereRaw('FIND_IN_SET(2,sent_mail_ids) = 0')
        ->where(DB::Raw('FIND_IN_SET("'.$id.'",mb.progress)'), '>', 0)
        ->orderByRaw('mb.last_name asc')
        ->get();

        return  compact('user','records');
    }

    public function memberUpdate(Request $request)
    {   

        $members = explode (",", $request->member_id); 

        foreach ($members as $key => $value){

             DB::table('members')
                ->where('id','=',$value)
                ->update(
                    [
                    'progress' => $request->progress_id,
                    ]
                );

        }

        
        
        return 'success';
    }

}