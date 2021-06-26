<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BillingController extends Controller
{

    public function index($store_name)
    {
        $records =[];
         $billings = DB::table('billings')
        ->select('*')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->orderByRaw('billing_code desc')
        ->get();

        foreach ($billings as $billing){

            $billing->billing_items =  DB::table('billing_items as bi')
            ->select('*')
            ->where('bi.token','=',$billing->token)
            ->get();

            array_push($records, $billing);
        }

        return  compact('records');
    }
    
    public function store(Request $request)
    {
 
      if($request->status == 3 && $request->billing_code){
         DB::table('billings')->insert(
            [
            'user_id'=>$request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'from' => $request->from,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'reference' => $request->reference,
            'subtotal' => $request->subtotal,
            'total_tax' => $request->total_tax,
            'total' => $request->total,
            'status' => (int)$request->status,
            'approved' => (int)$request->approved,
            'repeat_billing' => $request->repeat_billing,
            'billing_type' => $request->billing_type,
            'due_date_day' => $request->due_date_day,
            'due_date_instance' => $request->due_date_instance,
            'end_date' => $request->end_date,
            'ref_bill_code' => $request->billing_code,
            'paid_date' => $request->paid_date,
            'paid_from' => $request->paid_from,
            'reference_from' => $request->reference_from,
            'paid_total' => $request->paid_total,
             ]
        );
      }else{
        DB::table('billings')->insert(
            [
            'user_id'=>$request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'from' => $request->from,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'reference' => $request->reference,
            'subtotal' => $request->subtotal,
            'total_tax' => $request->total_tax,
            'total' => $request->total,
            'status' => (int)$request->status,
            'approved' => (int)$request->approved,
            'repeat_billing' => $request->repeat_billing,
            'billing_type' => $request->billing_type,
            'due_date_day' => $request->due_date_day,
            'due_date_instance' => $request->due_date_instance,
            'end_date' => $request->end_date,
            'repeat_billing' => $request->repeat_billing,
             ]
            );
        }
        foreach ($request->billing_items as $billing_item){ 

            DB::table('billing_items')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'item' => $billing_item['item'],
            'description' =>  $billing_item['description'],
            'quantity' =>  $billing_item['quantity'],
            'unit_price' =>  $billing_item['unit_price'],
            'tax_rate' =>  $billing_item['tax_rate'],
            'amount' =>  $billing_item['amount'],
            ]
            );

        } 
        
   
        $billing = DB::table('billings')
        ->select('*')
        ->where('token','=',$request->token)
        ->first();



        $billing_items = DB::table('billing_items as bi')
        ->select('bi.*'
        )
        ->where('bi.token','=',$request->token)
        ->get();


        if($billing){
            
           $billing->billing_items =  $billing_items;
        }
        
        if($request->status == 3){
            if($request->billing_code){
                $bi_code = 'BIL-'.strval($billing->id+1000);
            }else{
                
                $bi_code = 'REPBIL-'.strval($billing->id+1000);
            }
            
        }else{
            $bi_code = 'BIL-'.strval($billing->id+1000);
        }

        DB::table('billings')
        ->where('id','=',$billing->id)
        ->update(
            [
            'billing_code' => $bi_code
            ]
        );

        $billing->billing_code = $bi_code;
        $success ="success";

     
    $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "billings", "Stored new Billing");
      
        
        return compact('success','billing');
    }

    public function update(Request $request)
    {
        DB::table('billings')
        ->where('id', '=', $request->id)
        ->update(
            [
            'from' => $request->from,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'reference' => $request->reference,
            'subtotal' => $request->subtotal,
            'total_tax' => $request->total_tax,
            'total' => $request->total,
            'billing_type' => $request->billing_type,
            'due_date_day' => $request->due_date_day,
            'due_date_instance' => $request->due_date_instance,
            'end_date' => $request->end_date,
            'repeat_billing' => $request->repeat_billing,

            'status' => (int)$request->status,
            'approved' => (int)$request->approved,
            'paid_date' => $request->paid_date,
            'paid_from' => $request->paid_from,
            'reference_from' => $request->reference_from,
            'paid_total' => $request->paid_total,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('billing_items')
        ->where('token', '=', $request->token)
        ->delete();


        foreach ($request->billing_items as $billing_item){ 

            DB::table('billing_items')->insert(
            [
            'token' => $request->token,
            'store_name' => $request->store_name,
            'item' => $billing_item['item'],
            'description' =>  $billing_item['description'],
            'quantity' =>  $billing_item['quantity'],
            'unit_price' =>  $billing_item['unit_price'],
            'tax_rate' =>  $billing_item['tax_rate'],
            'amount' =>  $billing_item['amount'],
            ]
            );
        } 
   
        $billing = DB::table('billings')
        ->select('*')
        ->where('token','=',$request->token)
        ->first();


        $billing_items = DB::table('billing_items as bi')
        ->select('bi.*'
        )
        ->where('bi.token','=',$request->token)
        ->get();


        if($billing){
           $billing->billing_items =  $billing_items;
        }
        
        $success ="success";

     
    $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "billings", "Update Billing");
      
        
    return compact('success','billing');
    }

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('billings')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "billings", "Removed Billing");
        
        $updated_at = DB::table('billings')
            ->select(DB::raw('NOW() as updated_at'))
            ->first();

        return compact('updated_at');
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