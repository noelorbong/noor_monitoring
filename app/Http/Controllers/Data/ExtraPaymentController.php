<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ExtraPaymentController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('extra_payments')
        ->select('*')
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    { 
        DB::table('extra_payments')->insert(
            [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'token' => $request->token,
            'type' => $request->type,
            'rider_id' => $request->rider_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'paid_date' => $request->paid_date,
            'paid_date_time' => $request->paid_date_time,
            ]
        );
        
        $success ="success";

        $extra_payment =DB::table('extra_payments')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'extra_payments', "New extra payment Added");

        return compact('success','extra_payment');
    }

    public function update(Request $request)
    {
      
        DB::table('extra_payments')
        ->where('id','=',$request->id)
        ->update(
            [
            'type' => $request->type,
            'rider_id' => $request->rider_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'paid_date' => $request->paid_date,
            'paid_date_time' => $request->paid_date_time,
            'updated_at' => DB::raw('NOW()'),
            ]
        );
        
         
         $extra_payment =DB::table('extra_payments')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'extra_payments', "Update extra payment");

        return compact('success','extra_payment');
    }

    

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('extra_payments')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "extra_payments", "Removed extra payment");
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