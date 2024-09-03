<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User_Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\ProductCompositeItem;
use App\Traits\UplaodImageTraits;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Stripe\StripeClient;
use Stripe\Product as StripeProduct;
use Stripe\Stripe;
use Stripe\Price;

class ProductsController extends Controller
{

    use UplaodImageTraits;

    public function products(){

        $product=Product::where('user_id',Auth::user()->id)->paginate(20);

        return view('User.Products',['products' => $product]);
    }





     public function AddProductsForm(){

         $allProducts=Product::where(function($query){
            $query->where('is_composite_product','=',0)->
           where('user_id',Auth::user()->id);
         }
         )->get();
        return view('User.add_products',compact('allProducts'));
    }

    public function createproducts(ProductRequest $req) {

        try {

            DB::beginTransaction();

            $img = $this->UploadImage('products', $req->image);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $price = Price::create([
                'unit_amount' => $req->price * 100,
                'currency' => 'usd',
                'product_data' => [
                    'name' => $req->name,
                ],
            ]);

            // $IsComposite=$req->is_comopsite == 'on' ? 1 : 0;

            $product = new Product([
                'user_id' => Auth::user()->id,
                'name' => $req->name,
                'image' => $img,
                'price' => $req->price,
                'stripe_price_id' => $price->id,
                'description' => $req->description,
                'quantity'    => $req->quantity,

            ]);






            if($req->is_comopsite == 'on'){


                $product->is_composite_product = 1;

                $product->save();


                foreach ($req->composited_products as $index => $itemId) {


                    $quantity = $req->composited_quantities[$index];
                ProductCompositeItem::create([
                    'product_composite_id' =>$product->id,
                    'item_id'              =>$itemId,
                    'qty'                  =>$quantity

                ]);
            }
            }else{
                $product->save();

            }



            DB::commit();

            return redirect()->back()->with(['success' => 'New Product Added']);
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Error while adding new product']);
        }
    }


    public function UpdateProducts(Request $req)
    {
        try {
            $product = Product::where('user_id',Auth::user()->id)->find($req->id);
            if ($product) {

                Stripe::setApiKey(config('services.stripe.sk'));




                $newPrice = Price::create([
                    'unit_amount' => $req->price * 100,
                    'currency' => 'usd',
                    'product' => $product->stripe_product_id,
                ]);


                $product->update([
                    'user_id' => Auth::user()->id,
                    'name' => $req->name,
                    'price' => $req->price,
                    'stripe_price_id' => $newPrice->id,
                    'description' => $req->description,
                ]);


                if ($req->hasFile('image')) {
                    $des = 'storage/products/' . $product->image;

                    if (File::exists($des)) {
                        File::delete($des);
                    }

                    $img = $this->UploadImage('products', $req->image);
                    $product->image = $img;
                    $product->save();
                }

                return redirect()->back()->with(['success' => 'Product updated successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Product not found']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Error while updating product: ' . $ex->getMessage()]);
        }
    }

    public function DeleteProduct($id)
    {
        try {
            $product = Product::where('user_id',Auth::user()->id)->find($id);
            if ($product) {

                Stripe::setApiKey(config('services.stripe.sk'));


                $stripeProduct = StripeProduct::retrieve($product->stripe_product_id);
                $stripeProduct->delete();


                $des = 'storage/products/' . $product->image;
                if (File::exists($des)) {
                    File::delete($des);
                }


                $product->delete();

                return redirect()->back()->with(['success' => 'Product deleted successfully']);
            } else {
                abort(404,'Not Found');

            }
        } catch (\Exception $ex) {
                   abort(404,'Not Found');

        }
    }





}