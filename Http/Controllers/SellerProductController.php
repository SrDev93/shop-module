<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Base\Entities\Photo;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\Product;
use Modules\Shop\Entities\ProductProperty;
use Modules\Shop\Entities\ProductSeller;
use Modules\Shop\Entities\Seller;

class SellerProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Seller $seller)
    {
        $items = ProductSeller::where('seller_id', $seller->id)->get();
//        $items = Product::whereIn('id', $product_seller)->get();

        $olds = ProductSeller::where('seller_id', $seller->id)->pluck('product_id')->toArray();
        $products = Product::whereNotIn('id',$olds)->whereStatus(1)->get();

        return view('shop::seller_product.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request, Seller $seller)
    {
        if (isset($request->product_id) and $request->product_id){
            $product = Product::findOrFail($request->product_id);
        }else{
            $product = null;
        }

        $categories = Category::all();

        return view('shop::seller_product.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, Seller $seller)
    {
        try {

            if (isset($request->product_id) and $request->product_id){

                $price_off = null;
                if ($request->off and $request->price){
                    $price_off = $request->price * $request->off / 100;
                }

                $product_seller = ProductSeller::create([
                    'product_id' => $request->product_id,
                    'seller_id' => $seller->id,
                    'price' => $request->price,
                    'off' => $request->off,
                    'price_off' => $price_off,
                    'stock' => $request->stock,
                    'warranty' => $request->warranty,
                ]);

            }else {
                $item = Product::create([
                    'user_id' => Auth::id(),
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                    'short_text' => $request->short_text,
                    'description' => $request->description,
                    'img' => (isset($request->img) ? file_store($request->img, 'assets/uploads/photos/product_img/', 'photo_') : null),
                ]);

                if (isset($request->property_title)) {
                    foreach ($request->property_title as $key => $property_title) {
                        if ($property_title) {
                            $pp = ProductProperty::create([
                                'product_id' => $item->id,
                                'title' => $property_title,
                                'value' => $request->property_value[$key]
                            ]);
                        }
                    }
                }

                if (isset($request->photo)) {
                    foreach ($request->photo as $key => $photo) {
                        if (isset($photo) and $photo) {
                            $ph = new Photo();
                            $ph->path = file_store($photo, 'assets/uploads/photos/product_photos/', 'photo_');
                            $item->photo()->save($ph);
                        }
                    }
                }


                $price_off = null;
                if ($request->off and $request->price){
                    $price_off = $request->price * $request->off / 100;
                }

                $product_seller = ProductSeller::create([
                    'product_id' => $request->product_id,
                    'seller_id' => $seller->id,
                    'price' => $request->price,
                    'off' => $request->off,
                    'price_off' => $price_off,
                    'stock' => $request->stock,
                    'warranty' => $request->warranty,
                ]);
            }

            return redirect()->route('sellerProduct.index', $seller->id)->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('shop::seller_product.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Seller $seller, ProductSeller $ProductSeller)
    {
        $categories = Category::all();
        if (!Auth::user()->hasrole('seller')) {
            $product = Product::findOrFail($ProductSeller->product_id);
        }else{
            $product = null;
        }

        return view('shop::seller_product.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Seller $seller, ProductSeller $ProductSeller)
    {
        try {

//            $product = Product::findOrFail($ProductSeller->product_id);
//
//            $product->update([
//                'user_id' => Auth::id(),
//                'category_id' => $request->category_id,
//                'name' => $request->name,
//                'short_text' => $request->short_text,
//                'description' => $request->description,
//            ]);
//
//            if (isset($request->img)) {
//                if ($product->img){
//                    File::delete($product->img);
//                }
//                $product->img = file_store($request->img, 'assets/uploads/photos/product_img/', 'photo_');
//                $product->save();
//            }
//
//            if (isset($request->property_title)) {
//                foreach ($request->property_title as $key => $property_title) {
//                    if (isset($request->property_id[$key])){
//                        $pp = ProductProperty::findOrFail($request->property_id[$key]);
//                        if ($property_title){
//                            $pp->update([
//                                'title' => $property_title,
//                                'value' => $request->property_value[$key]
//                            ]);
//                        }else{
//                            $pp->delete();
//                        }
//                    }else {
//                        if ($property_title) {
//                            $pp = ProductProperty::create([
//                                'product_id' => $product->id,
//                                'title' => $property_title,
//                                'value' => $request->property_value[$key]
//                            ]);
//                        }
//                    }
//                }
//            }
//
//            if (isset($request->photo)) {
//                foreach ($request->photo as $key => $photo) {
//                    if (isset($request->photo[$key])) {
//                        if (isset($request->photo_id[$key])) {
//                            $ph = Photo::findOrFail($request->photo_id[$key]);
//                            if ($ph->path){
//                                File::delete($ph->path);
//                            }
//                            $ph->path = file_store($photo, 'assets/uploads/photos/product_photos/', 'photo_');
//                            $ph->save();
//
//                        } else {
//                            if (isset($photo) and $photo) {
//                                $ph = new Photo();
//                                $ph->path = file_store($photo, 'assets/uploads/photos/product_photos/', 'photo_');
//                                $product->photo()->save($ph);
//                            }
//                        }
//                    }
//                }
//            }


            $price_off = null;
            if ($request->off and $request->price){
                $price_off = $request->price * $request->off / 100;
            }

            $ProductSeller->update([
                'price' => $request->price,
                'off' => $request->off,
                'price_off' => $price_off,
                'stock' => $request->stock,
                'warranty' => $request->warranty,
            ]);

            return redirect()->route('sellerProduct.index', $seller->id)->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Seller $seller, ProductSeller $ProductSeller)
    {
        try {
            if (Auth::user()->hasrole('seller')) {
                if ($ProductSeller->seller->user_id == Auth::id()) {
                    $ProductSeller->delete();
                }else{
                    abort(403);
                }
            }else{
                $ProductSeller->delete();
            }

            return redirect()->route('sellerProduct.index', $seller->id)->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
