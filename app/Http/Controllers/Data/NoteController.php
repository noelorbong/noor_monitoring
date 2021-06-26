<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NoteController extends Controller
{

    public function index($store_name)
    {
        $records = DB::table('notes')
        ->select('*')
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    { 
        DB::table('notes')->insert(
            [
            'store_name' => $request->store_name,
            'user_id' => $request->user_id,
            'token' => $request->token,
            'note' => $request->note,
            'note_tags' => $request->note_tags,
            'shared' => (int)$request->shared,
            ]
        );
        
        $success ="success";

        $note =DB::table('notes')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();

        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, 'notes', "New note Added");

        return compact('success','note');
    }

    public function update(Request $request)
    {
      
        DB::table('notes')
        ->where('id','=',$request->id)
        ->update(
            [
            'note' => $request->note,
            'note_tags' => $request->note_tags,
            'shared' => (int)$request->shared,
            'updated_at' => DB::raw('NOW()'),
            ]
        );
        
         
         $note =DB::table('notes')
        ->select('*' )
        ->orderByRaw('created_at desc')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, 'notes', "Update note");

        return compact('success','note');
    }

    

    public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('notes')
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