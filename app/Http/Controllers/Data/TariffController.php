<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TariffController extends Controller
{

    public function updateDefaultLocation(Request $request)
    {
        DB::table('tariff_provinces')
        ->where('store_name','=',$request->store_name)
        ->update( [
            'default' => 0,
            ]
        );

        DB::table('tariff_provinces')
        ->where('store_name','=',$request->store_name)
        ->where('id','=',$request->province_id)
        ->update( [
            'default' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('tariff_municipalities')
        ->where('store_name','=',$request->store_name)
        ->update( [
            'default' => 0,
            ]
        );

        DB::table('tariff_municipalities')
        ->where('store_name','=',$request->store_name)
        ->where('id','=',$request->municipality_id)
        ->update( [
            'default' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        DB::table('tariff_barangays')
        ->where('store_name','=',$request->store_name)
        ->update( [
            'default' => 0,
            ]
        );

        DB::table('tariff_barangays')
        ->where('store_name','=',$request->store_name)
        ->where('id','=',$request->barangay_id)
        ->update( [
            'default' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", "", "tariff_provinces", "default Tariff location Updated");

        return compact('success');
    }

    //#region Province
    public function indexProvince($store_name)
    {
        $records = DB::table('tariff_provinces as tp')
        ->select('tp.*')
        ->orderByRaw('province asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function storeProvince(Request $request)
    {
        DB::table('tariff_provinces')->insert(
            [
            'token' => $request->token,
            'user_id' => $request->user_id,
            'store_name' => $request->store_name,
            'province' => $request->province,
            ]
        );
        
        $success ="success";

        $tariff_province =DB::table('tariff_provinces as tp')
        ->select('tp.*')
        ->orderByRaw('province asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "tariff_provinces", "Tariff province Store");

        return compact('success','tariff_province');
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