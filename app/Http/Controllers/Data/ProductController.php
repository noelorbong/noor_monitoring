<?php
namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

      public function ckUpLoadImage(Request $request)
    {

        $photo = 'add_image.png';
        if($request->file('upload')){
            $photo = str_random(25).'png';
            $cover = $request->file('upload');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put( $photo,  File::get($cover));
        }

        $customer_json = array("uploaded"=>true,"url"=>"/uploads/".$photo);
        return $customer_json;
    }

    public function upLoadImage(Request $request)
    {
        if($request->file('photo')){
            $photo = $request->filename;
            $cover = $request->file('photo');
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put( $photo.'.png',  File::get($cover));
        }
        
        return 'success';
    }

    public function index($store_name)
    {
         $records = DB::table('products')
        ->select('*')
        ->where('store_name','=',$store_name)
        ->where('delete','=',0)
        ->orderByRaw('title asc')
        ->get();

        return  compact('records');
    }
    
    public function store(Request $request)
    {
 
    
        DB::table('products')->insert(
            [
            'user_id' => $request->user_id,
            'token' => $request->token,
            'store_name' => $request->store_name,
            'title' => $request->title,
            'description' => $request->description,
            'images' => $request->images,
            'price' => $request->price,
            'compare_at_price' => $request->compare_at_price,
            'cost_per_item' => $request->cost_per_item,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'quantity' => $request->quantity,
            'incentives' => $request->incentives,
            'product_type' => $request->product_type,
            'vendor' => $request->vendor,
            'collections' => $request->collections,
            'tags' => $request->tags,
            ]
        );

        $product = DB::table('products')
        ->select('*')
        ->where('store_name','=',$request->store_name)
        ->where('token','=',$request->token)
        ->first();
        
        $this->logEvent($request->store_name, $request->user_id, "Store", $request->token, "products", "Stored new product");
        $success ="success";

        return compact('success','product');
    }

    public function update(Request $request)
    {
      
        DB::table('products')
        ->where('id','=',$request->id)
        ->update(
            [
            'title' => $request->title,
            'description' => $request->description,
            'images' => $request->images,
            'price' => $request->price,
            'compare_at_price' => $request->compare_at_price,
            'cost_per_item' => $request->cost_per_item,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'quantity' => $request->quantity,
            'incentives' => $request->incentives,
            'product_type' => $request->product_type,
            'vendor' => $request->vendor,
            'collections' => $request->collections,
            'tags' => $request->tags,
            'updated_at' => DB::raw('NOW()')
            ]
        );
        
        $product = DB::table('products')
        ->select('*')
        ->where('store_name','=',$request->store_name)
         ->where('id','=',$request->id)
        ->first();
        
        $success ="success";

        $this->logEvent($request->store_name, $request->user_id, "Update", $request->token, "products", "Update product");

        return compact('success','product');
    }

     public function updateCollection(Request $request)
    {
        //  return $request->input();
        foreach ($request->input() as $product){ 
            
        DB::table('products')
        ->where('id','=',$product['id'])
        ->update(
            [
            'collections' => $product['collections'],
            'updated_at' => DB::raw('NOW()')
            ]
            );
        

          $this->logEvent($product['store_name'], $product['user_id'], "Update", $product['token'], "products", "Update product collection");
        } 
        
        $success ="success";

        return compact('success');
    }


      public function delete($id, $store_name, $user_id, $token )
    {
        DB::table('products')
        ->where('id','=',$id)
        ->update(
            [
            'delete' => 1,
            'updated_at' => DB::raw('NOW()')
            ]
        );

        $this->logEvent($store_name, $user_id, "Delete", $token, "products", "product Removed");
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