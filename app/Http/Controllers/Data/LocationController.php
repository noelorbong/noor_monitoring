<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LocationController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('locations as lo')
        ->select('lo.*')
        ->orderByRaw('title asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    {
 
        
        DB::table('locations')->insert(
            [
            'token' => $request->token,
            'user_id' => $request->user_id,
            'store_name' => $request->store_name,
            'title' => $request->title,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'zipcode' => $request->zipcode
            ]
        );

       
        
        $success ="success";

        $location =DB::table('locations as lo')
        ->select('lo.*')
        ->orderByRaw('barangay asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "locations", "Location Store");

        return compact('success','location');
    }

    public function update(Request $request)
    {
        DB::table('locations')
        ->where('id','=',$request->id)
        ->update([
            'title' => $request->title,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'updated_at' => DB::raw('NOW()')
            ]
        );

       
        
        $success ="success";

        $location =DB::table('locations as lo')
        ->select('lo.*')
        ->orderByRaw('barangay asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "locations", "Location Update");

        return compact('success','location');
    }

      public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('locations')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "locations", "Location Removed");
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