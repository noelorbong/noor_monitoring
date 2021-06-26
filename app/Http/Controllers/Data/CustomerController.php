<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('customers as mb')
        ->select('mb.*'
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
        )
        ->orderByRaw('last_name asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    {
 
        $photo = "image_icon";
        if($request->file('photo')){
            $photo = str_random(25);
            $cover = $request->file('photo');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put( $photo.'.png',  File::get($cover));
        }

        // return $request;
        
        DB::table('customers')->insert(
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
            'zipcode' => $request->zipcode,
            'tags' => $request->tags,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_link' => $request->location_link,
            ]
        );

        $success ="success";

        $customer =DB::table('customers as mb')
        ->select('mb.*'
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
        )
        ->orderByRaw('last_name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'customers', "New Customer Added");

        return compact('success','customer');
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
        
        DB::table('customers')
        ->where('id','=',$request->id)
        ->update(
            [
            'photo' =>  $photo.'.png',
            'last_name' => $request->last_name == "null"? "":$request->last_name,
            'first_name' => $request->first_name == "null"? "":$request->first_name,
            'name_extension' => $request->name_extension == "null"? "":$request->name_extension,
            'middle_name' => $request->middle_name == "null"? "":$request->middle_name,
            'fb_name' => $request->fb_name == "null"? "":$request->fb_name,
            'email_address' => $request->email_address == "null"? "":$request->email_address,
            'contact_number' => $request->contact_number == "null"? "":$request->contact_number,
            'dob' => $request->dob == "null"? null:$request->dob,
            'gender' => $request->gender,

            'address1' => $request->address1 == "null"? "":$request->address1,
            'address2' => $request->address2 == "null"? "":$request->address2,
            'barangay' => $request->barangay == "null"? "":$request->barangay,
            'municipality' => $request->municipality == "null"? "":$request->municipality,
            'province' => $request->province == "null"? "":$request->province,
            'zipcode' => $request->zipcode == "null"? "":$request->zipcode,
            'tags' => $request->tags == "null"? "":$request->tags,
            'latitude' => $request->latitude == "null"? "":$request->latitude,
            'longitude' => $request->longitude == "null"? "":$request->longitude,
            'location_link' => $request->location_link == "null"? "":$request->location_link,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        
         
         $customer = DB::table('customers as mb')
        ->select('mb.*'
            ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
            ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
        )
        ->orderByRaw('last_name asc')
        ->where('id','=',$request->id)
        ->where('delete','=',0)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'customers', "Update Customer");

        return compact('success','customer');
    }

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('customers')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "customers", "Removed Customer");
        return 'success';
    }

    public function logEvent($store_name, $user_id, $event, $token, $table, $description){
         DB::table('event_logger')->insert(
            [
            'store_name' =>  $store_name,
            'user_id' => $user_id,
            'event' => $event,
            'table' => $table,
            'description' => $description ,
            'token' => $token
            ]
        );
    }
   

   
}