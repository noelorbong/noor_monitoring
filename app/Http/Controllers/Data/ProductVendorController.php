<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductVendorController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('product_vendors')
        ->select('*')
        ->orderByRaw('name asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    { 
        DB::table('product_vendors')->insert(
            [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'token' => $request->token,
            'name' => $request->name,
            'color' => $request->color,
            ]
        );
        
        $success ="success";

        $product_vendor =DB::table('product_vendors')
        ->select('*' )
        ->orderByRaw('name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'product_vendors', "New product vendor Added");

        return compact('success','product_vendor');
    }

    public function update(Request $request)
    {
      
        DB::table('product_vendors')
        ->where('id','=',$request->id)
        ->update(
            [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'color' => $request->color,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        
         
         $product_vendor =DB::table('product_vendors')
        ->select('*' )
        ->orderByRaw('name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'product_vendors', "Update product vendor");

        return compact('success','product_vendor');
    }

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('product_vendors')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "product_vendors", "Removed product vendor");
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