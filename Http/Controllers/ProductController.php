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

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $items = Product::all();

        return view('shop::product.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $categories = Category::all();

        return view('shop::product.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $item = Product::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'name' => $request->name,
                'short_text' => $request->short_text,
                'description' => $request->description,
                'img' => (isset($request->img) ? file_store($request->img, 'assets/uploads/photos/product_img/', 'photo_') : null),
            ]);

            if (isset($request->property_value)) {
                foreach ($request->property_value as $key => $property_value) {
                    if ($property_value) {
                        $pp = ProductProperty::create([
                            'product_id' => $item->id,
                            'property_id' => $request->property_id[$key],
                            'value' => $property_value
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

            return redirect()->route('product.index')->with('flash_message', 'با موفقیت انجام شد');
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
        return view('shop::product.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('shop::product.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Product $product)
    {
        try {

            $product->update([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'name' => $request->name,
                'short_text' => $request->short_text,
                'description' => $request->description,
            ]);

            if (isset($request->img)) {
                if ($product->img){
                    File::delete($product->img);
                }
                $product->img = file_store($request->img, 'assets/uploads/photos/product_img/', 'photo_');
                $product->save();
            }

            if (!isset($request->old_property_id) and count($product->properties)){
                $product->properties()->delete();
            }

            if (isset($request->property_value)) {
                foreach ($request->property_value as $key => $property_value) {
                    if (isset($request->old_property_id[$key])){
                        $pp = ProductProperty::findOrFail($request->old_property_id[$key]);
                        if ($property_value){
                            $pp->update([
                                'property_id' => $request->property_id[$key],
                                'value' => $property_value
                            ]);
                        }else{
                            $pp->delete();
                        }
                    }else {
                        if ($property_value) {
                            $pp = ProductProperty::create([
                                'product_id' => $product->id,
                                'property_id' => $request->property_id[$key],
                                'value' => $property_value
                            ]);
                        }
                    }
                }
            }

            if (isset($request->photo)) {
                foreach ($request->photo as $key => $photo) {
                    if (isset($request->photo[$key])) {
                        if (isset($request->photo_id[$key])) {
                            $ph = Photo::findOrFail($request->photo_id[$key]);
                            if ($ph->path){
                                File::delete($ph->path);
                            }
                            $ph->path = file_store($photo, 'assets/uploads/photos/product_photos/', 'photo_');
                            $ph->save();

                        } else {
                            if (isset($photo) and $photo) {
                                $ph = new Photo();
                                $ph->path = file_store($photo, 'assets/uploads/photos/product_photos/', 'photo_');
                                $product->photo()->save($ph);
                            }
                        }
                    }
                }
            }

            return redirect()->route('product.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Product $product)
    {
        try {

            $product->delete();

            return redirect()->route('product.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function status(Product $product)
    {
        try {

            $product->status = !$product->status;
            $product->save();

            return redirect()->route('product.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function fetch_property($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $category->properties()->select('id', 'name')->get()
            ]);

        }catch (\Exception $e){
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
    }
}
