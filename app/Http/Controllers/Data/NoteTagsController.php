<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NoteTagsController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('note_tags')
        ->select('*')
        ->orderByRaw('name asc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    { 
        DB::table('note_tags')->insert(
            [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'token' => $request->token,
            'name' => $request->name,
            'color' => $request->color,
            ]
        );
        
        $success ="success";

        $note_tag =DB::table('note_tags')
        ->select('*' )
        ->orderByRaw('name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'note_tags', "New note tags Added");

        return compact('success','note_tag');
    }

    public function update(Request $request)
    {
      
        DB::table('note_tags')
        ->where('id','=',$request->id)
        ->update(
            [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'color' => $request->color,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        
         
         $note_tag =DB::table('note_tags')
        ->select('*' )
        ->orderByRaw('name asc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'note_tags', "Update note tag");

        return compact('success','note_tag');
    }

    

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('note_tags')
            ->where('id','=',$id)
            ->update(
                    [
                    'delete' => 1,
                    'updated_at' => DB::raw('NOW()')
                    ]
                );
        
        $this->logEvent($store_name, $user_id, "Delete", $token, "note_tags", "Removed note tag");
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