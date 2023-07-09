<?php

namespace Modules\Shop\Http\Controllers;

use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Entities\ProductSeller;

class ShopController extends Controller
{
    public function amazing()
    {
        if (Auth::user()->hasrole('seller')) {
            if (!Auth::user()->seller){
                return redirect()->back()->with('err_message', 'لطفا پروفایل خود را تکمیل نمایید');
            }
            $seller = Auth::user()->seller;

            $items = ProductSeller::where('seller_id', $seller->id)->get();
        }else{
            $items = ProductSeller::all();
        }

        return view('shop::amazing.index', get_defined_vars());
    }

    public function amazing_update(Request $request)
    {
        try {
            if (isset($request->products)){
                foreach ($request->products as $key => $prod){
                    $product = ProductSeller::findOrFail($prod);
                    $product->off = $request->off[$key];
                    if ($request->amazing_date[$key] and $request->off[$key]) {
                        $product->amazing_date = Verta::parse($request->amazing_date[$key])->datetime()->format('Y-m-d H:i:s');
                    }elseif (!$request->off[$key]){
                        $product->amazing_date = null;
                    }

                    $price_off = null;
                    if ($request->off[$key] and $product->price){
                        $price_off = $product->price * $request->off[$key] / 100;
                        $price_off = $product->price - $price_off;
                    }
                    $product->price_off = $price_off;

                    $product->save();
                }
            }

            return redirect()->back()->with('flash_message', 'بروزرسانی با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
