<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Http;

class ShopifySettingController extends Controller
{

    public function requestAPI(Request $request){
       // $link = "https://105911994e5297c746b7368c5ea56ed1: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@farmers-marketplace-ph.myshopify.com/admin/api/2020-10/products.json";
       
       
       $xml = file_get_contents($request->link);
        // $response = Http::get('https://105911994e5297c746b7368c5ea56ed1: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@farmers-marketplace-ph.myshopify.com/admin/api/2020-10/products.json');

        return $xml;
    }

    

    public function index($store_name)
    {
        $records = DB::table('shopify_settings')
        ->select('*')
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$store_name)
        ->get();

        return  compact('records');
    }

    public function updateStoreSetting(Request $request)
    {
        $count_exist =DB::table('shopify_settings')
            ->select(DB::raw('count(*) as setting_count'))
            ->where('token', '=', $request->token)
            ->first();
        
        if ($count_exist->setting_count > 0){
            return $this->update($request);
        }else{
            return $this->store($request);
        }
    }

    public function update(Request $request)
    {
      
        DB::table('shopify_settings')
        ->where('id','=',$request->id)
        ->update(
            [
            'api' => $request->api,
            'updated_at' => DB::raw('NOW()'),
            ]
        );
        
         
        $shopify_setting =DB::table('shopify_settings')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'shopify_settings', "Shopify Setting");

        return compact('success','shopify_setting');
    }
    
    public function store(Request $request)
    { 
        DB::table('shopify_settings')->insert(
            [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'token' => $request->token,
            'api' => $request->api,
            'description' => $request->description
            ]
        );
        
        $shopify_setting =DB::table('shopify_settings')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'shopify_settings', "New shopify setting Added");

        return compact('success','shopify_setting');
    }

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('shopify_settings')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "notes", "Removed note");
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