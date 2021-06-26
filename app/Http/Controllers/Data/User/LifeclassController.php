<?php
namespace App\Http\Controllers\Data\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LifeclassController extends Controller
{
      
    public function index($tribe_id)
    {
        $records = DB::table('lifeclasses as ct')
        ->select(
            'ct.*'
            ,DB::Raw('(select count(*) from members where  FIND_IN_SET(ct.id,members.lifeclass) > 0 and members.tribe = '.$tribe_id.') as lifeclass_member')
            
           )
        ->orderByRaw('ct.lifeclass_name asc')
        ->get();

        return  compact('records');
    }

    public function store(Request $request)
    {    
        DB::table('lifeclasses')->insert(
            [
            'lifeclass_name' => $request->lifeclass_name,
            'description' => $request->description
            ]
        );
        
        return 'success';
    }

     public function edit($id)
    {    
        
         $record = DB::table('lifeclasses')
        ->select(
            '*'     
           )
        ->where('id','=',$id)
        ->first();

        return  compact('record');
    }

    public function update(Request $request)
    {
        DB::table('lifeclasses')
        ->where('id','=',$request->id)
        ->update([
            'lifeclass_name' => $request->lifeclass_name,
            'description' => $request->description,
            'updated_at' => DB::Raw("NOW()")
            ]
        );
        
        return 'success';
    }

    public function delete($id)
    {
    
    DB::table('lifeclasses')->where('id', '=', $id)->delete();

    $users = DB::table('members')
            ->select(
                'members.*'
            )
            ->where(DB::raw('FIND_IN_SET('.$id.',members.lifeclass)') , '>', 0)
            ->get();

    foreach ($users as $user){

    
        $str_arr = explode (",", $user->lifeclass);
        $array1= array();

        foreach ($str_arr as $value){ 
            if($value != $id){
                array_push($array1,$value);
            } 
        } 

        if(count($array1) > 0){
            $new_lifeclass = join( ',', $array1);
        }else{
            $new_lifeclass = "1";
        }

        DB::table('members')
        ->where('id','=', $user->id)
        ->update(
                [
                'lifeclass' => $new_lifeclass,
                'updated_at' => DB::Raw("NOW()")
                ]
            );
    }
        return 'success';
    }

    public function remove($id,$lifeclass_id)
    {
        $user = DB::table('members')
            ->select(
                'members.*'
            )
            ->where('id','=',$id)
            ->first();

        $str_arr = explode (",", $user->lifeclass);
        $array1= array();

        foreach ($str_arr as $value){ 
            if($value != $lifeclass_id){
                array_push($array1,$value);
            } 
        } 

    if(count($array1) > 0){
            $new_lifeclass = join( ',', $array1);
        }else{
            $new_lifeclass = "1";
        }

    DB::table('members')
      ->where('id','=',$id)
      ->update(
            [
            'lifeclass' => $new_lifeclass,
            'updated_at' => DB::Raw("NOW()")
            ]
        );

        return 'success';
    }

    public function profile($tribe_id,$id)
    {
        $user = DB::table('lifeclasses as ct')
        ->select(
            'ct.*'
             ,DB::Raw('(select count(*) from members where  FIND_IN_SET(ct.id,members.lifeclass) > 0   and members.tribe = '.$tribe_id.' ) as lifeclass_member')
            
           )
        ->where('id','=',$id)
        ->first();

         $records = DB::table('members as mb')
        ->leftJoin('tribes', 'mb.tribe', '=', 'tribes.id')
        //->leftJoin('lifeclasses', 'mb.lifeclass', '=', 'lifeclasses.id')
        ->select('mb.*'
        ,'tribes.tribe_name'
        //,'lifeclasses.lifeclass_name'
        ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
        ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name') 
        ,DB::raw('(Select concat(COALESCE(last_name,""),", ",COALESCE(first_name,"")," ",COALESCE(name_extension,"")," ",COALESCE(middle_name,"")) from members where id = mb.mentor limit 1) as mentor_name')
        )
       // ->whereRaw('FIND_IN_SET(2,sent_mail_ids) = 0')
        ->where(DB::Raw('FIND_IN_SET("'.$id.'",mb.lifeclass)'), '>', 0)
        ->where('mb.tribe','=',$tribe_id)
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
                'lifeclass' => $request->lifeclass_id,
                ]
            );

            // $lifeclass_id = "1";

            // $user = DB::table('members')
            // ->select(
            //     'members.*'
            // )
            // ->where('id','=',$value)
            // ->first();

            // if($user->lifeclass == "1"){
            //     $lifeclass_id = $request->lifeclass_id;
            // }else{
            //     $lifeclass_id = $user->lifeclass.",".$request->lifeclass_id;
            // }

            //  DB::table('members')
            //     ->where('id','=',$value)
            //     ->update(
            //         [
            //         'lifeclass' => $lifeclass_id,
            //         ]
            //     );

        }
        
        return 'success';
    }

}