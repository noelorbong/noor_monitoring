<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    public function testApi($store_name){
        $result = DB::table('customers')
        ->where('reference','=',15550)
        ->count();

        return $result;
    }

    public function multipleDelete(Request $request)
    {
        
        DB::table('orders')
        ->whereIn('token',$request->orders)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('orders_item')
        ->whereIn('token',$request->orders)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('orders_shipping')
        ->whereIn('token',$request->orders)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        //$this->logEvent($store_name, $user_id, "Delete", $token, "orders", "Order Removed");
        return 'success';
    }


    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('orders')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('orders_item')
        ->where('token','=',$token)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('orders_shipping')
        ->where('token','=',$token)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "orders", "Order Removed");
        return 'success';
    }

    public function indexPending($store_name)
    {
        $records =[];
        $records =[];
         $orders = DB::table('orders')
        ->select('*')
        ->where('store_name','=',$store_name)
        ->where('status','!=',2)
        ->where('delete','=',0)
        ->orderByRaw('invoice_code desc')
        ->get();

        $ordersToken = $orders->pluck('token');

        // return $ordersToken;

        $records = $orders;

        $customer =  DB::table('orders_shipping as os')
            ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
            ->select('customers.id',
            DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
            DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
            'os.token',
            'customers.photo',
            'customers.fb_name',
            'os.customer_id',
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
            ->where('os.store_name','=',$store_name)
            ->whereIn('os.token',$ordersToken)
            ->where('os.delete','=',0)
            ->get();

        $orders =  DB::table('orders_item as oi')
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
        ->where('oi.store_name','=',$store_name)
        ->whereIn('oi.token',$ordersToken)
        ->where('oi.delete','=',0)
        ->get();

        return  compact('records','customer','orders');
    }

    public function index($store_name)
    {
        $orders_count = DB::table('orders')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->count();

         $records = DB::table('orders')
        ->select('*')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->orderByRaw('invoice_code desc')
        
        ->get();
        
        $ordersToken = $records->pluck('token');
       

        $customer =  DB::table('orders_shipping as os')
            ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
            ->select('customers.id',
            DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
            DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
            'os.token',
            'customers.photo',
            'customers.fb_name',
            'os.customer_id',
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
            ->where('os.store_name','=',$store_name)
            ->whereIn('os.token',$ordersToken)
            ->where('os.delete','=',0)
            ->get();

        $orders =  DB::table('orders_item as oi')
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
        ->where('oi.store_name','=',$store_name)
        ->whereIn('oi.token',$ordersToken)
        ->where('oi.delete','=',0)
        ->get();

        return  compact('records','customer','orders','orders_count');

         foreach ($orders as $order){
            $customer =  DB::table('orders_shipping as os')
                ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
                ->select('customers.id',
                DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
                DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
                'customers.token',
                'customers.photo',
                'customers.fb_name',
                'os.customer_id',
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
                ->where('os.token','=',$order->token)
                ->first();

            if(!$customer){
                $customer_json = array("id"=>"","full_name"=>"","complete_address"=>"","token"=>"",
                "photo"=>"","customer_id"=>"","first_name"=>"","last_name"=>"","email_address"=>"","contact_number"=>"",
                "address1"=>"","address2"=>"","barangay"=>"","municipality"=>"","province"=>"","zipcode"=>"");
            $customer = $customer_json;
        }

 
            $order->customer = $customer;


            $order->orders =  DB::table('orders_item as oi')
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
            ->where('oi.token','=',$order->token)
            ->get();
            array_push($records, $order);
         }

        return  compact('records');
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
    
    public function store(Request $request)
    {
 
    
        DB::table('orders')->insert(
            [
            'user_id'=>$request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'discount_reason' => $request->discount_reason,
            'subtotal' => $request->subtotal,
            'custom_rate_name' => $request->custom_rate_name,
            'shipping_fee' => $request->shipping_fee,
            'total' => $request->total,
            'total_incentives'  => $request->total_incentives,
            'note' => $request->note,
            'rider' => (int)$request->rider,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'delivery_method' => $request->delivery_method,
            'rider_incentive' => $request->rider_incentive,
            'rider_incentive_reason' => $request->rider_incentive_reason,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
            'paid_date' => $request->paid_date,
            'order_date_time' => $request->order_date_time,
            'delivery_date_time' => $request->delivery_date_time,
            'paid_date_time' => $request->paid_date_time,
            'tags' => $request->tags
            ]
        );

         DB::table('orders_shipping')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'customer_id' => $request->customer['id'],
            'last_name' => $request->customer['last_name'],
            'first_name' => $request->customer['first_name'],
            'email_address' => $request->customer['email_address'],
            'contact_number' => $request->customer['contact_number'],
            'address1' => $request->customer['address1'],
            'address2' => $request->customer['address2'],
            'barangay' => $request->customer['barangay'],
            'municipality' => $request->customer['municipality'],
            'province' => $request->customer['province'],
            'zipcode' => $request->customer['zipcode']
            ]
        );

        foreach ($request->orders as $order_item){ 

            DB::table('orders_item')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'product_id' => $order_item['id'],
            'title' =>  $order_item['title'],
            'price' =>  $order_item['price'],
            'compare_at_price' =>  $order_item['compare_at_price'],
            'cost_per_item' =>  $order_item['cost_per_item'],
            'quantity' =>  (int)$order_item['quantity'],
            'incentives' => $order_item['incentives']
            ]
            );

            if((int)$request->status == 1 || (int)$request->status == 2){
                DB::table('products')
                ->where('id','=',$order_item['id'])
                ->update(
                    [
                    'quantity' => (int)$order_item['in_stock']-(int)$order_item['quantity'],
                    ]
                );
            }


        } 
        
   
        $order = DB::table('orders as or')
        ->select('or.*')
        ->where('or.token','=',$request->token)
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
        ->where('os.token','=',$request->token)
        ->first();

         $orders = DB::table('orders_item as oi')
        ->leftjoin('products as pr', 'oi.product_id', '=', 'pr.id')
        ->select('oi.*',
        'pr.description',
        'pr.quantity as in_stock',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(oi.price, 0))*(IFNULL(oi.quantity, 0))))as total')
        )
        ->where('oi.token','=',$request->token)
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
        
        $inv_code = 'Inv-'.strval($order->id+1000);

        DB::table('orders')
        ->where('id','=',$order->id)
        ->update(
            [
            'invoice_code' => $inv_code
            ]
        );

        $order->invoice_code = $inv_code;
        $success ="success";

        if($request->status == 0){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Created Draft Order");
        }else if($request->status == 1){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Stored Pending Order");
        }else if($request->status == 2){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Stored Paid Order");
        }
        
        return compact('success','order');
    }

    public function shopifyStore(Request $request)
    {
 
    
        DB::table('orders')->insert(
            [
            'user_id'=>$request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'discount_reason' => $request->discount_reason,
            'subtotal' => $request->subtotal,
            'custom_rate_name' => $request->custom_rate_name,
            'shipping_fee' => $request->shipping_fee,
            'total' => $request->total,
            'total_incentives'  => $request->total_incentives,
            'note' => $request->note,
            'rider' => (int)$request->rider,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'delivery_method' => $request->delivery_method,
            'rider_incentive' => $request->rider_incentive,
            'rider_incentive_reason' => $request->rider_incentive_reason,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
            'paid_date' => $request->paid_date,
            'order_date_time' => $request->order_date_time,
            'delivery_date_time' => $request->delivery_date_time,
            'paid_date_time' => $request->paid_date_time,
            'tags' => $request->tags,
            'from' => $request->from,
            'reference' => $request->reference
            ]
        );

        $count_customer_exist = DB::table('customers')
        ->where('reference','=',$request->customer['reference'])
        ->count();

        if($count_customer_exist <= 0){
            DB::table('customers')->insert(
                [
                'token' => $request->customer['token'],
                'user_id'=>$request->user_id,
                'store_name' => $request->store_name,
                'photo' =>  'image_icon.png',
                'last_name' => $request->customer['last_name'],
                'first_name' => $request->customer['first_name'],
                'email_address' => $request->customer['email_address'],
                'contact_number' => $request->customer['contact_number'],
                'address1' => $request->customer['address1'],
                'address2' => $request->customer['address2'],
                'barangay' => $request->customer['barangay'],
                'municipality' => $request->customer['municipality'],
                'province' => $request->customer['province'],
                'zipcode' => $request->customer['zipcode'],
                'from' => $request->customer['from'],
                'reference' => $request->customer['reference']
                ]
            );
        }

        $customer_new = DB::table('customers')
        ->select('*')
        ->where('reference','=',$request->customer['reference'])
        ->first();

        

         DB::table('orders_shipping')->insert(
            [
            'token' => $request->token,
            'store_name' => $customer_new->store_name,
            'customer_id' => $customer_new->id,
            'last_name' => $request->customer['last_name'],
            'first_name' => $request->customer['first_name'],
            'email_address' => $request->customer['email_address'],
            'contact_number' => $request->customer['contact_number'],
            'address1' => $request->customer['address1'],
            'address2' => $request->customer['address2'],
            'barangay' => $request->customer['barangay'],
            'municipality' => $request->customer['municipality'],
            'province' => $request->customer['province'],
            'zipcode' => $request->customer['zipcode'],
            ]
        );

        foreach ($request->orders as $order_item){ 

            DB::table('orders_item')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'product_id' => $order_item['id'],
            'title' =>  $order_item['title'],
            'price' =>  $order_item['price'],
            'compare_at_price' =>  $order_item['compare_at_price'],
            'cost_per_item' =>  $order_item['cost_per_item'],
            'quantity' =>  (int)$order_item['quantity'],
            'incentives' => $order_item['incentives']
            ]
            );

            if((int)$request->fulfilled == 1){
                DB::table('products')
                ->where('id','=',$order_item['id'])
                ->update(
                    [
                    'quantity' => (int)$order_item['in_stock']-(int)$order_item['quantity'],
                    ]
                );
            }


        } 
        
   
        $order = DB::table('orders as or')
        ->select('or.*')
        ->where('or.token','=',$request->token)
        ->first();

        $customer = DB::table('orders_shipping as os')
        ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
        ->select('customers.id',
        DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
        DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
        'customers.token',
        'customers.photo',
        'customers.from',
        'customers.reference',
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
        ->where('os.token','=',$request->token)
        ->first();

        $orders = DB::table('orders_item as oi')
        ->leftjoin('products as pr', 'oi.product_id', '=', 'pr.id')
        ->select('oi.*',
        'pr.description',
        'pr.quantity as in_stock',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(oi.price, 0))*(IFNULL(oi.quantity, 0))))as total')
        )
        ->where('oi.token','=',$request->token)
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
        
        $inv_code = 'Inv-'.strval($order->id+1000);

        DB::table('orders')
        ->where('id','=',$order->id)
        ->update(
            [
            'invoice_code' => $inv_code
            ]
        );

        $order->invoice_code = $inv_code;
        $success ="success";

        if($request->status == 0){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Created Draft Order");
        }else if($request->status == 1){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Stored Pending Order");
        }else if($request->status == 2){
            $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "orders", "Stored Paid Order");
        }
        
        return compact('success','order','customer_new');
    }

    public function update(Request $request)
    {
        $previous_status = DB::table('orders as or')
        ->select('fulfilled','status')
        ->where('or.token','=',$request->token)
        ->first();

        DB::table('orders')
        ->where('token','=',$request->token)
        ->update(
            [
            'discount_type' => $request->discount_type,
            'discount' => $request->discount,
            'discount_reason' => $request->discount_reason,
            'subtotal' => $request->subtotal,
            'custom_rate_name' => $request->custom_rate_name,
            'shipping_fee' => $request->shipping_fee,
            'total' => $request->total,
            'total_incentives'  => $request->total_incentives,
            'note' => $request->note,
            'delivery_method' => $request->delivery_method,
            'tracking_number' => $request->tracking_number,
            'fulfilled' => $request->fulfilled,
            'status' => $request->status,
            'payment_type' => $request->payment_type,
            'order_date' => $request->order_date,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
            'paid_date' => $request->paid_date,
            'order_date_time' => $request->order_date_time,
            'delivery_date_time' => $request->delivery_date_time,
            'paid_date_time' => $request->paid_date_time,
            'tags' => $request->tags,
            'rider_incentive' => $request->rider_incentive,
            'rider_incentive_reason' => $request->rider_incentive_reason,
            'rider' => (int)$request->rider,
            'cancel_reason' => $request->cancel_reason,
            'updated_at'=> DB::raw('NOW()')
            ]
        );

        if($request->customer){
         DB::table('orders_shipping')
         ->where('token','=',$request->token)
         ->update(
            [
            'customer_id' => $request->customer['id'],
            'last_name' => $request->customer['last_name'],
            'first_name' => $request->customer['first_name'],
            'email_address' => $request->customer['email_address'],
            'contact_number' => $request->customer['contact_number'],
            'address1' => $request->customer['address1'],
            'address2' => $request->customer['address2'],
            'barangay' => $request->customer['barangay'],
            'municipality' => $request->customer['municipality'],
            'province' => $request->customer['province'],
            'zipcode' => $request->customer['zipcode'],
            'updated_at' => DB::raw('NOW()')
            ]
        );
    }

     if((int)$previous_status->status != 3 && (int)$request->order_type == 2 && $request->from !='shopify'){
        $previous_fullfilled_items = DB::table('orders_item as oi')
        ->select('*')
        ->where('oi.token','=',$request->token)
        ->get();

         foreach ($previous_fullfilled_items as $item){
             $in_stock = DB::table('products')
                        ->select('quantity')
                        ->where('id','=',$item->product_id)
                        ->first();

              DB::table('products')
                ->where('id','=',$item->product_id)
                ->update(
                    [
                    'quantity' => (int)$in_stock->quantity + (int)$item->quantity,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
         }
     }

        DB::table('orders_item')
        ->where('token', '=', $request->token)
        ->delete();

        foreach ($request->orders as $order_item){ 
            DB::table('orders_item')->insert(
                    [
                    'token' => $request->token,
                    'store_name' => $request->store_name,
                    'product_id' => $order_item['product_id'],
                    'title' =>  $order_item['title'],
                    'price' =>  $order_item['price'],
                    'compare_at_price' =>  $order_item['compare_at_price'],
                    'cost_per_item' =>  $order_item['cost_per_item'],
                    'incentives' => $order_item['incentives'],
                    'quantity' =>  (int)$order_item['quantity'],
                    ]
                );

            if((int)$previous_status->status != 1 && (int)$request->order_type != 2){
                if((int)$request->status == 1 && $request->from !='shopify'){
                    $in_stock = DB::table('products')
                            ->select('quantity')
                            ->where('id','=',$order_item['product_id'])
                            ->first();

                    DB::table('products')
                    ->where('id','=',$order_item['product_id'])
                    ->update(
                        [
                        'quantity' => (int)$in_stock->quantity - (int)$order_item['quantity'],
                        'updated_at' => DB::raw('NOW()')
                        ]
                    );
                } 
            } 

            if(((int)$previous_status->status == 3 || (int)$previous_status->status == 0) && (int)$request->order_type != 2){
                if((int)$request->status == 2 && $request->from !='shopify'){
                    $in_stock = DB::table('products')
                            ->select('quantity')
                            ->where('id','=',$order_item['product_id'])
                            ->first();

                    DB::table('products')
                    ->where('id','=',$order_item['product_id'])
                    ->update(
                        [
                        'quantity' => (int)$in_stock->quantity - (int)$order_item['quantity'],
                        'updated_at' => DB::raw('NOW()')
                        ]
                    );
                } 
            }

            if((int)$request->status != 3 && (int)$request->status != 0 && (int)$request->order_type == 2){
                if($request->from !='shopify'){
                    $in_stock = DB::table('products')
                            ->select('quantity')
                            ->where('id','=',$order_item['product_id'])
                            ->first();

                    DB::table('products')
                    ->where('id','=',$order_item['product_id'])
                    ->update(
                        [
                        'quantity' => (int)$in_stock->quantity - (int)$order_item['quantity'],
                        'updated_at' => DB::raw('NOW()')
                        ]
                    );
                } 
            }
        }
        
        $order = DB::table('orders as or')
        ->select('or.*')
        ->where('or.token','=',$request->token)
        ->first();

        $customer = DB::table('orders_shipping as os')
        ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
        ->select('customers.id',
        DB::raw('concat(COALESCE(os.address1,""),", ",COALESCE(os.address2,"")," ",COALESCE(os.barangay,"")," ",COALESCE(os.zipcode,"")," ",COALESCE(os.municipality,"")," ",COALESCE(os.province,"")) as complete_address '),
        DB::raw('concat(COALESCE(customers.last_name,""),", ",COALESCE(customers.first_name,"")," ",COALESCE(customers.name_extension,"")," ",COALESCE(customers.middle_name,"")) as full_name'),
        'customers.token',
        'customers.photo',
        'customers.fb_name',
        'os.customer_id',
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
        ->where('os.token','=',$request->token)
        ->first();

        $orders = DB::table('orders_item as oi')
        ->leftjoin('products as pr', 'oi.product_id', '=', 'pr.id')
        ->select('oi.*',
        'pr.description',
        'pr.quantity as in_stock',
        'pr.images',
        'pr.sku',
        'pr.barcode',
        'pr.product_type',
        'pr.vendor',
        'pr.collections',
        'pr.tags',
         DB::Raw('(Select((IFNULL(oi.price, 0))*(IFNULL(oi.quantity, 0))))as total')
        )
        ->where('oi.token','=',$request->token)
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
        
        $success ="success";
        if($request->status == 0 && $previous_status->status != $request->status){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order Move to Draft");
        }else if($request->status == 1 && $previous_status->status != $request->status){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order Set to Pending");
        }else if($request->status == 2 && $previous_status->status != $request->status){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order Set to Paid");
        }else if($request->status == 3 && $previous_status->status != $request->status){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Cancel Order");
        }else if($request->status == 4 && $previous_status->status != $request->status){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order set to Waitlist");
        }
        else{
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order updated");
        }

        if($request->fulfilled == 1 && $previous_status->fulfilled != $request->fulfilled){
            $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Order fulfilled");
        }

        return compact('success','order');
    }


    public function updateMultiple(Request $request)
    {
         foreach ($request->orders as $order){ 
            //  return $order['token'];
            $previous_status = DB::table('orders as or')
            ->select('fulfilled','status')
            ->where('or.token','=',$order['token'])
            ->first();

            DB::table('orders')
            ->where('token','=',$order['token'])
            ->update(
                [
            
                'delivery_method' => $order['delivery_method'],
                'tracking_number' => $order['tracking_number'],
                'fulfilled' => $order['fulfilled'],
                'status' =>$order['status'],
                'payment_type' => $order['payment_type'],
                'paid_date' => $order['paid_date'],
                'paid_date_time' => $order['paid_date_time'],
                'updated_at'=> DB::raw('NOW()')
                ]
            );

            if($previous_status->fulfilled != $order['fulfilled']){
                $this->logEvent($order['store_name'], $request->user_id, "Update", $order['token'], "orders", "Order fulfilled Update");
            }

            if($previous_status->status != $order['status']){
                $this->logEvent($order['store_name'], $request->user_id, "Update", $order['token'], "orders", "Order Status Update");
            }

        }

        $updated_at = DB::table('orders')
            ->select(DB::raw('NOW() as updated_at'))
            ->first();

        return compact('updated_at');
    }


    public function updateRider(Request $request){
        DB::table('orders')
        ->where('token','=',$request->token)
        ->update(
            [
            'rider' => (int)$request->rider,
            'updated_at'=> DB::raw('NOW()')
            ]
        );

         

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Rider Updated");
        
        $updated_at = DB::table('orders')
            ->select(DB::raw('NOW() as updated_at'))
            ->first();

        return compact('updated_at');
    }

    public function updatePayment(Request $request){
        DB::table('orders')
        ->where('token','=',$request->token)
        ->update(
            [
            'delivery_method' => $request->delivery_method,
            'payment_type' => $request->payment_type,
            'updated_at'=> DB::raw('NOW()')
            ]
        );

         

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Payment or Delivery Updated");
        
        $updated_at = DB::table('orders')
            ->select(DB::raw('NOW() as updated_at'))
            ->first();

        return compact('updated_at');
    }

    public function updateRiderIncentive(Request $request){
        DB::table('orders')
        ->where('token','=',$request->token)
        ->update(
            [
            'rider_incentive' => $request->rider_incentive,
            'updated_at'=> DB::raw('NOW()')
            ]
        );

         

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "orders", "Rider Incentive Updated");
        
        $order = DB::table('orders')
        ->select('*')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        return compact('order');
    }

    

   

   

   
}