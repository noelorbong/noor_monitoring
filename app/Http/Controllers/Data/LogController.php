<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{

    public function index(Request $request)
    {
        $records = [];

        if($request->state == 'first_load'){
            $records = DB::table('event_logger')
                ->select('*')
                ->where('store_name','=',$request->store_name)
                ->orderByRaw('updated_at desc')
                ->limit((int)$request->limit )
                ->get();
                
            
        }else{
            $logs = DB::table('event_logger')
                ->select('*')
                ->where('store_name','=',$request->store_name)
                ->where(DB::RAW('TIMESTAMP(updated_at)'),'>',DB::RAW("TIMESTAMP('".$request->updated_at."')"))
                ->orderByRaw('updated_at desc')
                ->get();

            foreach ($logs as $log){
                $log->data = $this->event($log);
                 array_push($records, $log);
            }
            
            
        }
        
        return  compact('records');
    }

     public function event($log)
    {
        $log_event = null;

        if($log->table == 'orders'){
            $log_event =  $this->indexOrder($log);
        }else if($log->table == 'billings'){
            $log_event =  $this->indexBilling($log);
        }else if($log->table == 'customers'){
             $log_event =  $this->indexCustomer($log);
        }else{
            $log_event =DB::table($log->table)
            ->select('*' )
            ->where('store_name','=',$log->store_name)
            ->where('token','=',$log->token)
            ->first();
        }
        
        return  $log_event;
    }
    
  
    public function indexCustomer($log)
    {
         $customer =DB::table('customers as mb')
            ->select('mb.*'
                ,DB::raw('concat(COALESCE(mb.address1,""),", ",COALESCE(mb.address2,"")," ",COALESCE(mb.barangay,"")," ",COALESCE(mb.zipcode,"")," ",COALESCE(mb.municipality,"")," ",COALESCE(mb.province,"")) as complete_address ')
                ,DB::raw('concat(COALESCE(mb.last_name,""),", ",COALESCE(mb.first_name,"")," ",COALESCE(mb.name_extension,"")," ",COALESCE(mb.middle_name,"")) as full_name')
            )
            ->orderByRaw('last_name asc')
            ->where('store_name','=',$log->store_name)
            ->where('token','=',$log->token)
            ->first();

        return $customer;
    }

    public function indexBilling($log)
    {
                $billing = DB::table('billings')
        ->select('*')
        ->where('token','=',$log->token)
        ->first();



        $billing_items = DB::table('billing_items as bi')
        ->select('bi.*'
        )
        ->where('bi.token','=',$log->token)
        ->get();


        if($billing){
            
           $billing->billing_items =  $billing_items;
        }

        return $billing;
    }

       public function indexOrder($log)
    {
        $order = DB::table('orders as or')
        ->select('or.*')
        ->where('or.token','=',$log->token)
        ->first();

        $customer = DB::table('orders_shipping as os')
        ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
        ->select('customers.id',
        DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
        DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
        'customers.token',
        'customers.photo',
        'os.customer_id',
        'customers.fb_name',
        'os.first_name',
        'os.last_name',
        'os.email_address',
        'os.contact_number',
        'os.address1',
        'os.address2',
        'os.barangay',
        'os.municipality',
        'os.province',
        'os.zipcode'
        )
        ->where('os.token','=',$log->token)
        ->first();

        $orders = DB::table('orders_item as oi')
        ->leftjoin('products as pr', 'oi.product_id', '=', 'pr.id')
        ->select('oi.*',
        'pr.description',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(oi.price, 0))*(IFNULL(oi.quantity, 0))))as total')
        )
        ->where('oi.token','=',$log->token)
        ->get();

        if(!$customer){
            $customer_json = array("id"=>"","full_name"=>"","complete_address"=>"","token"=>"",
            "photo"=>"","customer_id"=>"","first_name"=>"","last_name"=>"","email_address"=>"","contact_number"=>"",
            "address1"=>"","address2"=>"","barangay"=>"","municipality"=>"","province"=>"","zipcode"=>"");
            $customer = $customer_json;
        }

        if($order){
           $order->customer =  $customer;
           $order->orders =  $orders;
        }
        
      
        return $order;
    }
   

   
}