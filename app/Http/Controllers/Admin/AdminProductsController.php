<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\UplaodImageTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Stripe\StripeClient;
class AdminProductsController extends Controller
{
    use UplaodImageTraits;



    public function ShowProducts(){
        $product=Product::paginate(20);

        return view('Admin.Products',['products' => $product]);
    }

    public function AddProductsForm(){
        return view('Admin.add_products');
    }

    public function createproducts(Request $req){
        try{

            DB::beginTransaction();


            $img=$this->UploadImage('products', $req->image);

            $product = new Product([
                'name' => $req->name,
                'image'=>$img,
                'price'=>$req->price,
                'description'=>$req->description,

            ]);

            $product->createStripePlans();
            DB::commit();

            return redirect()->back()->with(['success'=>'New Product Added']);

        }
            catch(\Exception $ex){

                return $ex;
                DB::rollBack();

            return redirect()->back()->with(['error'=>'Error while adding new product']);

        }
    }


    public function UpdateProducts(Request $req){
        try{


        $product=Product::find($req->id);
        if($product){





            $product->update([
                'name'       =>$req->name,
                'price'      =>$req->price,
                'description'=>$req->description,

            ]);


            if ($req->hasFile('image')) {


                $des ='storage/products/' . $product->image;


          if (File::exists($des)) {
               File::delete($des);

      }

      $img=$this->UploadImage('products',$req->image);
      $product->image=$img;
      $product->save();
    }
        return redirect()->back();
        }else{
            return redirect()->back()->with(['error'=>'Error while deleting product']);

        }
    }catch(\Exception $ex)
    {
        return $ex;
        return redirect()->back()->with(['error'=>'Error while deleting product']);

    }

    }

    public function DeleteProducts($id){
        $product=Product::find($id);
        try{

            DB::beginTransaction();
        if($product){

            if ($product->image) {


                $des ='storage/products/' . $product->image;


          if (File::exists($des)) {
               File::delete($des);

      }

            $product->delete();


            DB::commit();

            return redirect()->back();
        }

    }
    } catch(\Exception $ex){
        DB::rollBack();
        return redirect()->back()->with(['error'=>'Error while deleting product']);

    }

}

}