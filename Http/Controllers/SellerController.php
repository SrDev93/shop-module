<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Account\Entities\User;
use Modules\Shop\Entities\ProductSeller;
use Modules\Shop\Entities\Seller;
use Modules\Shop\Entities\SellerDoc;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (isset($request->product_id) and $request->product_id){
            $product_seller = ProductSeller::where('product_id', $request->product_id)->pluck('seller_id')->toArray();
            $items = Seller::whereIn('id', $product_seller)->get();
        }else {
            $items = Seller::all();
        }

        return view('shop::seller.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $users = User::role('seller')->get();

        return view('shop::seller.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $seller = Seller::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'logo' => (isset($request->logo)?file_store($request->logo, 'assets/uploads/photos/seller_logo/', 'photo_'):null),
                'banner' => (isset($request->banner)?file_store($request->banner, 'assets/uploads/photos/seller_banner/', 'photo_'):null),
            ]);

            if (isset($request->docs)){
                foreach ($request->docs as $doc){
                    $seller_doc = new SellerDoc();
                    $seller_doc->seller_id = $seller->id;
                    $seller_doc->path = file_store($doc, 'assets/uploads/documents/sellers/', 'doc_');
                    $seller_doc->save();
                }
            }

            if (Auth::user()->hasrole('seller')){
                return redirect()->route('admin.home')->with('flash_message', 'با موفقیت انجام شد');
            }else {
                return redirect()->route('seller.index')->with('flash_message', 'با موفقیت انجام شد');
            }
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
        return view('shop::seller.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Seller $seller)
    {
        $users = User::role('seller')->get();

        return view('shop::seller.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Seller $seller)
    {
        try {
            $seller->update([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram
            ]);

            if (isset($request->logo)) {
                if ($seller->logo){
                    File::delete($seller->logo);
                }
                $seller->logo = file_store($request->logo, 'assets/uploads/photos/seller_logo/', 'photo_');
            }
            if (isset($request->banner)) {
                if ($seller->banner){
                    File::delete($seller->banner);
                }
                $seller->banner = file_store($request->banner, 'assets/uploads/photos/seller_banner/', 'photo_');
            }
            $seller->save();

            if (isset($request->docs)){
                foreach ($request->docs as $doc){
                    $seller_doc = new SellerDoc();
                    $seller_doc->seller_id = $seller->id;
                    $seller_doc->path = file_store($doc, 'assets/uploads/documents/sellers/', 'doc_');
                    $seller_doc->save();
                }
            }

            return redirect()->route('seller.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Seller $seller)
    {
        try {
            $seller->delete();

            return redirect()->route('seller.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function status(Seller $seller)
    {
        try {

            $seller->status = !$seller->status;
            $seller->save();

            return redirect()->route('seller.index')->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function doc_delete($id)
    {
        $item = SellerDoc::findOrFail($id);
        try {
            $item->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
