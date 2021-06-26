<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('users')
        ->select('id',
        'store_name',
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'role',
        'admin',
        'active',
        'delete',
        'created_at',
        'updated_at')

        ->orderByRaw('last_name asc')
        ->where('store_name','=',$store_name)
        ->where('delete','!=',1)
        ->get();

        return  compact('records');
    }

    public function store(Request $request){

        $email_count = DB::table('users')
            ->select(DB::raw('count(*) as email_count'))
            ->where('email','=',$request->email)
            ->first();

        $error = array('email' => 'email already exist');
        if($email_count->email_count >=1){
            return compact('error');
        }

        DB::table('users')
            ->insert([
                    'store_name' => $request->store_name,
                    'token' => $request->token,
                    'user_id' => $request->user_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'mobile_number' => $request->mobile_number,
                    'role'=> 2,
                    'active'=> $request->active,
                    'admin'=> $request->admin,
                    'delete'=> 0,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $user =DB::table('users as mb')
        ->select('*')
        ->orderByRaw('last_name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $this->logEvent($request->store_name, $request->user_id, $request->event, $request->token, "users", $request->event_description, $request->history);
        $success ="success";
        return compact('success','user');
    
    }

    public function update(Request $request){
        DB::table('users')
            ->where('id','=',$request->id)
            ->update(
                    [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile_number' => $request->mobile_number,
                    'role'=> $request->role,
                    'active'=>$request->active,
                    'admin'=>$request->admin,
                    'delete'=>$request->delete,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($request->store_name, $request->user_id, $request->event, $request->token, "users", $request->event_description, $request->history);
        return 'success';
    
    }
    
    public function _store(Request $request)
    {
 
        $photo = "image_icon";
        if($request->file('photo')){
            $photo = str_random(25);
            $cover = $request->file('photo');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put( $photo.'.png',  File::get($cover));
        }

        // return $request;
        
        DB::table('riders')->insert(
            [
            'photo' =>  $photo.'.png',
            'token' => $request->token,
            'user_id' => $request->user_id,
            'store_name' => $request->store_name,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'name_extension' => $request->name_extension,
            'middle_name' => $request->middle_name,
            'fb_name' => $request->fb_name,
            'email_address' => $request->email_address,
            'contact_number' => $request->contact_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'zipcode' => $request->zipcode
            ]
        );

       
        
        $success ="success";

        $rider =DB::table('riders as mb')
        ->select('mb.*'
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
        )
        ->orderByRaw('last_name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'riders', "New Rider Added");

        return compact('success','rider');
    }



    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('riders')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "riders", "Removed Rider");
        return 'success';
    }

    public function logEvent($store_name, $user_id, $event, $token, $table, $description,$history){
         DB::table('event_logger')->insert(
            [
            'store_name' =>  $store_name,
            'user_id' => $user_id,
            'event' => $event,
            'table' => $table,
            'description' => $description ,
            'token' => $token,
            'history' => $history
            ]
        );
    }
   

   
}