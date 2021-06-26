<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SMSController extends Controller
{

    //#region Province
    public function index()
    {
        $records = DB::table('smsmessageall as sms')
        // ->leftJoin('customers', 'os.customer_id', '=', 'customers.id')
        ->select('sms.*')
        ->orderByRaw('CreatedAt desc')
        ->get();

        return  compact('records');
    }

    
    public function newMessage($message_id)
    {
        $records = DB::table('smsmessageall as sms')
        // ->leftJoin('customers', 'sms.', 'like', DB::raw('customers.contact_number'))
        // ->leftJoin('customers', function ($join) {
        //     $join->on(db::raw('CONCAT("%",SUBSTRING(sms.MessageFrom,-10 , 10))'), '=', db::raw('CONCAT("%",SUBSTRING(customers.contact_number,-10 , 10))'));
        //     $join->orOn(db::raw('CONCAT("%",SUBSTRING(sms.MessageTo,-10 , 10))'), '=', db::raw('CONCAT("%",SUBSTRING(customers.contact_number,-10 , 10))'));
        //     // $join->on('sms.MessageFrom', 'like', DB::raw("CONCAT('%',SUBSTRING(customers.contact_number,-10 , 10)"));
        //     // ->orOn(...);
        // })
        ->select('sms.*')
        ->where('sms.id','>',$message_id)
        ->orderByRaw('CreatedAt desc')
        ->get();

        return  compact('records');
    }

    public function smsTemplates($store_name)
    {
        $sms_templates = DB::table('smstemplates as sms')
        ->select('sms.*')
        ->where('sms.store_name','=',$store_name)
        ->where('sms.delete','=',0)
        ->get();

        return  compact('sms_templates');
    }

    public function insertTemplate(Request $request)
    {
          DB::table('smstemplates')
            ->insert( [
                'store_name' => $request->store_name,
                'user_id' => $request->user_id,
                'token' => $request->token,
                'number' => $request->number,
                'name' => $request->name,
                'label' => $request->label,
                'template' => $request->template
                ]
            );

        $sms_template = DB::table('smstemplates as sms')
        ->select('sms.*')
        ->where('sms.token','=',$request->token)
        ->first();

        return  compact('sms_template');
    }

      public function selectTemplate(Request $request)
    {
        DB::table('smstemplates')
            ->where('name','=', $request->name)
            ->update( [
                'name' => '',
                'selected' => 0,
                'updated_at' => DB::raw('NOW()')
                ]
        );

         DB::table('smstemplates')
            ->where('id','=', $request->id)
            ->update( [
                'name' => $request->name,
                'selected' => $request->selected
                ]
        );

        $sms_template = DB::table('smstemplates as sms')
        ->select('sms.*')
        ->where('sms.token','=',$request->token)
        ->first();

        return  compact('sms_template');
    }

    public function templateDelete($id)
    {
        DB::table('smstemplates')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        // $this->logEvent($store_name, $user_id, "Delete", $token, "tariff_provinces", "Tariff Province Removed");
        return 'success';
    }

    public function updateTemplate(Request $request)
    {
         DB::table('smstemplates')
            ->where('id','=', $request->id)
            ->update( [
                'template' => $request->template,
                'number' => $request->number,
                'label' => $request->label,
                'updated_at' => DB::raw('NOW()')
                ]
            );

        $sms_template = DB::table('smstemplates as sms')
        ->select('sms.*')
        ->where('sms.token','=',$request->token)
        ->first();

        return  compact('sms_template');
    }

    public function setRead($number,$read)
    {
        if(strlen($number)>=11){
            $number = substr($number,-10);
        }

        DB::table('smsmessageall')
        ->where('MessageTo','like', '%'.$number)
        ->orWhere('MessageFrom','like', '%'.$number)
        ->update( [
            'ReadMessage' => $read,
            'UpdateAt' => DB::raw('NOW()')
            ]
        );

        // $message =DB::table('smsmessageall')
        // ->select('*')
        // ->where('id','=',$id)
        // ->first();

        $success ="success";


        // $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "tariff_provinces", "Tariff province Update");

        return compact('success');
    }


    
    public function sendMessage(Request $request)
    {
        DB::table('smsmessageouts')->insert(
            [
            'MessageTo' => $request->number,
            'MessageText' => $request->message,
            'MessageFrom' => $request->Username,
            'token' => $request->token
            ]
        );
        
        $success ="success";

        // $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "tariff_provinces", "Tariff province Store");

        return compact('success');
    }

     public function sendMultipleMessage(Request $request)
    {
        //return $request;
        foreach ($request->sms as $req){ 

             DB::table('smsmessageouts')->insert(
            [
            'MessageTo' => $req['number'],
            'MessageText' => $req['message'],
            'MessageFrom' => $req['Username'],
            'token' => $req['token']
            ]
        );

        }
       
        
        $success ="success";

        // $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "tariff_provinces", "Tariff province Store");

        return compact('success');
    }

    public function updateProvince(Request $request)
    {
        DB::table('tariff_provinces')
        ->where('id','=',$request->id)
        ->update( [
            'province' => $request->province,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $success ="success";

        $tariff_province =DB::table('tariff_provinces as tp')
        ->select('tp.*')
        ->orderByRaw('province asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "tariff_provinces", "Tariff province Update");

        return compact('success','tariff_province');
    }

    public function deleteProvince($id, $store_name, $user_id, $token )
    {
        DB::table('tariff_provinces')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "tariff_provinces", "Tariff Province Removed");
        return 'success';
    }

    //#endregion

    //#region Municipalities
    public function indexMunicipalities($store_name)
    {
        $records = DB::table('tariff_municipalities as tm')
        ->select('tm.*')
        ->orderByRaw('municipality asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function storeMunicipalities(Request $request)
    {
        DB::table('tariff_municipalities')->insert(
            [
            'token' => $request->token,
            'user_id' => $request->user_id,
            'store_name' => $request->store_name,
            'province_id' => $request->province_id,
            'municipality' => $request->municipality,
            'zipcode' => $request->zipcode,
            ]
        );
        
        $success ="success";

        $tariff_municipality =DB::table('tariff_municipalities as tm')
        ->select('tm.*')
        ->orderByRaw('municipality asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "tariff_municipalities", "Tariff municipality Store");

        return compact('success','tariff_municipality');
    }

    public function updateMunicipalities(Request $request)
    {
        DB::table('tariff_municipalities')
        ->where('id','=',$request->id)
        ->update( [
            'municipality' => $request->municipality,
            'zipcode' => $request->zipcode,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $success ="success";

        $tariff_municipality =DB::table('tariff_municipalities as tp')
        ->select('tp.*')
        ->orderByRaw('municipality asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "tariff_municipalities", "Tariff municipality Update");

        return compact('success','tariff_municipality');
    }

    public function deleteMunicipalities($id, $store_name, $user_id, $token )
    {
        DB::table('tariff_municipalities')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "tariff_municipalities", "Tariff Municipality Removed");
        return 'success';
    }
    

    //#endregion

    //#region Barangays
    public function indexBarangays($store_name)
    {
        $records = DB::table('tariff_barangays as tb')
        ->select('tb.*')
        ->orderByRaw('barangay asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function storeBarangays(Request $request)
    {
        DB::table('tariff_barangays')->insert(
            [
            'token' => $request->token,
            'user_id' => $request->user_id,
            'store_name' => $request->store_name,
            'province_id' => $request->province_id,
            'municipality_id' => $request->municipality_id,
            'barangay' => $request->barangay,
            'distance' => (float)$request->distance,
            'rider_fee' => (float)$request->rider_fee,
            ]
        );
        
        $success ="success";

        $tariff_barangay =DB::table('tariff_barangays as tb')
        ->select('tb.*')
        ->orderByRaw('barangay asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "tariff_barangays", "Tariff barangay Store");

        return compact('success','tariff_barangay');
    }

    public function updateBarangays(Request $request)
    {
        DB::table('tariff_barangays')
        ->where('id','=',$request->id)
        ->update( [
            'province_id' => $request->province_id,
            'municipality_id' => $request->municipality_id,
            'barangay' => $request->barangay,
            'distance' => $request->distance,
            'rider_fee' => $request->rider_fee,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $success ="success";

        $tariff_barangay =DB::table('tariff_barangays as tb')
        ->select('tb.*')
        ->orderByRaw('barangay asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "tariff_barangays", "Tariff barangay Update");

        return compact('success','tariff_barangay');
    }

    public function deleteBarangays($id, $store_name, $user_id, $token )
    {
        DB::table('tariff_barangays')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "tariff_barangays", "Tariff barangay Removed");
        return 'success';
    }
    

    //#endregion

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