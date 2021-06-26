<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TransferController extends Controller
{


    public function index($store_name)
    {
        $records =[];
         $transfers = DB::table('transfers')
        ->select('*')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->orderByRaw('order_date desc')
        ->get();


         foreach ($transfers as $transfer){

        $transfer->products =  DB::table('transfers_item as ti')
        ->leftjoin('products as pr', 'ti.product_id', '=', 'pr.id')
        ->select('ti.*',
        'pr.description',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
        DB::Raw('(Select((IFNULL(ti.price, 0))*(IFNULL(ti.quantity, 0))))as total')
        )
        ->where('ti.token','=',$transfer->token)
        ->get();

        $transfer->origin = json_decode($transfer->origin);
        $transfer->destination = json_decode($transfer->destination);

        array_push($records, $transfer);
         }

        return  compact('records');
    }
    
    public function store(Request $request)
    {
 
    
        DB::table('transfers')->insert(
            [
            'user_id'=>$request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'note' => $request->note,
            'origin' => json_encode($request->origin),
            'destination' => json_encode($request->destination),
            'expected_arrival' => $request->expected_arrival,
            'tracking_number' => $request->tracking_number,
            'reference_number' => $request->reference_number,
            'tags' => $request->tags,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'discount_reason' => $request->discount_reason,
            'subtotal' => $request->subtotal,
            'custom_rate_name' => $request->custom_rate_name,
            'shipping_fee' => $request->shipping_fee,
            'total' => $request->total,
            'note' => $request->note,
            'rider' => (int)$request->rider,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'order_date' => $request->order_date,

            ]
        );

        foreach ($request->products as $product_item){ 

            DB::table('transfers_item')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'product_id' => $product_item['id'],
            'title' =>  $product_item['title'],
            'price' =>  $product_item['price'],
            'compare_at_price' =>  $product_item['compare_at_price'],
            'cost_per_item' =>  $product_item['cost_per_item'],
            'quantity' =>  (int)$product_item['quantity'],
            'quantity_success' =>  (int)$product_item['quantity_success'],
            'quantity_cancel' =>  (int)$product_item['quantity_cancel'],
            'quantity_reject' =>  (int)$product_item['quantity_reject'],
            
            ]
            );

           


        } 
        
        $transfer = DB::table('transfers as tr')
        ->select('tr.*')
        ->where('tr.token','=',$request->token)
        ->first();

        $products = DB::table('transfers_item as ti')
        ->leftjoin('products as pr', 'ti.product_id', '=', 'pr.id')
        ->select('ti.*',
        'pr.description',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(ti.price, 0))*(IFNULL(ti.quantity, 0))))as total')
        )
        ->where('ti.token','=',$request->token)
        ->get();
        
        $transfer->products = $products;
        $transfer->origin = json_decode($transfer->origin);
        $transfer->destination = json_decode($transfer->destination);
        $transfer_code = 'T-'.strval($transfer->id+1000);

        DB::table('transfers')
        ->where('id','=',$transfer->id)
        ->update(
            [
            'transfer_code' => $transfer_code,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $transfer->transfer_code = $transfer_code;
        $success ="success";

         $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "transfers", "Stored New Trasfer");
        return compact('success','transfer');
    }

    public function update(Request $request)
    {
        DB::table('transfers')
        ->where('token','=',$request->token)
        ->update(
            [
            'note' => $request->note,
            'origin' => json_encode($request->origin),
            'destination' => json_encode($request->destination),
            'expected_arrival' => $request->expected_arrival,
            'tracking_number' => $request->tracking_number,
            'reference_number' => $request->reference_number,
            'tags' => $request->tags,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'discount_reason' => $request->discount_reason,
            'subtotal' => $request->subtotal,
            'custom_rate_name' => $request->custom_rate_name,
            'shipping_fee' => $request->shipping_fee,
            'total' => $request->total,
            'note' => $request->note,
            'rider' => (int)$request->rider,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'order_date' => $request->order_date,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        
    
        DB::table('transfers_item')
        ->where('token', '=', $request->token)
        ->delete();

        foreach ($request->products as $product_item){ 

            DB::table('transfers_item')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'product_id' => $product_item['product_id'],
            'title' =>  $product_item['title'],
            'price' =>  $product_item['price'],
            'compare_at_price' =>  $product_item['compare_at_price'],
            'cost_per_item' =>  $product_item['cost_per_item'],
            'quantity' =>  (int)$product_item['quantity'],
            'quantity_success' =>  (int)$product_item['quantity_success'],
            'quantity_cancel' =>  (int)$product_item['quantity_cancel'],
            'quantity_reject' =>  (int)$product_item['quantity_reject'],
            
            ]
            );
        } 
        
        $transfer = DB::table('transfers as tr')
        ->select('tr.*')
        ->where('tr.token','=',$request->token)
        ->first();

        $products = DB::table('transfers_item as ti')
        ->leftjoin('products as pr', 'ti.product_id', '=', 'pr.id')
        ->select('ti.*',
        'pr.description',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(ti.price, 0))*(IFNULL(ti.quantity, 0))))as total')
        )
        ->where('ti.token','=',$request->token)
        ->get();
        
        $transfer->products = $products;
        $transfer->origin = json_decode($transfer->origin);
        $transfer->destination = json_decode($transfer->destination);
               
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "transfers", "Update Trasfer");

        return compact('success','transfer');
    }

     public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('transfers')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        $this->logEvent($store_name, $user_id, "Delete", $token, "transfers", "Transfer Removed");
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