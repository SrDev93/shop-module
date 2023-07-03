<?php

namespace Modules\Shop\Http\Controllers\Front;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Modules\IrCity\Entities\ProvinceCity;
use Modules\Shop\Entities\Basket;
use Modules\Shop\Entities\Factor;
use Modules\Shop\Entities\Product;
use Modules\Shop\Entities\ProductSeller;
use Modules\Shop\Entities\Transaction;
use Modules\Shop\Entities\UserAddress;

class ShopController extends Controller
{
    public function checkout()
    {
        if (!Cookie::has('user')) {
            $factor = null;
        }else {
            $user = Cookie::get('user');
            $factor = Factor::where('user_session', $user)->whereStatus(0)->latest()->first();
        }
        if (!$factor || !count($factor->baskets)){
            return redirect()->back()->with('err_message', 'سبد خرید شما خالی می باشد');
        }

        $factor->user_id = Auth::id();
        $factor->save();

        $user_address = UserAddress::where('user_id', Auth::id())->get();

        $provinces = ProvinceCity::whereNull('parent_id')->get();
//        $cities = ProvinceCity::whereNotNull('parent_id')->get();
        $cities = ProvinceCity::whereNotNull('parent_id')->select(['id', 'parent_id', 'name'])->get();

        return view('checkout', get_defined_vars());
    }

    public function submit_order(Request $request)
    {
        $request->validate([
            'address_id' => 'required'
        ]);

        if (!Cookie::has('user')) {
            $factor = null;
        }else {
            $user = Cookie::get('user');
            $factor = Factor::where('user_session', $user)->whereStatus(0)->latest()->first();
        }
        if (!$factor || !count($factor->baskets)){
            return redirect()->route('home')->with('err_message', 'سبد خرید شما خالی می باشد');
        }

        $factor->address_id = $request->address_id;
        $factor->save();

        $transaction = Transaction::create([
            'factor_id' => $factor->id
        ]);

        // redirect to Payment gateway
        return redirect()->route('callback'); // Temporary
        // redirect to Payment gateway

        return redirect()->back()->with('flash_message', 'سفارش شما با موفقیت ثبت شد');
    }

    public function callback()
    {
        if (!Cookie::has('user')) {
            $factor = null;
        }else {
            $user = Cookie::get('user');
            $factor = Factor::where('user_session', $user)->whereStatus(0)->latest()->first();
        }
        if (!$factor || !count($factor->baskets)){
            return redirect()->route('home')->with('err_message', 'سبد خرید شما خالی می باشد');
        }

        $transaction = Transaction::where('factor_id', $factor->id)->latest()->first();
        $transaction->status = 1;
        $transaction->tracking_code = uniqid();
        $transaction->save();

        $factor->status = 1;
        $factor->pay_status = 'paid';
        $factor->save();

        if ($factor->status){

            foreach ($factor->baskets as $basket){
                if ($basket->product and $basket->seller_product) {
                    $seller_product = $basket->seller_product;
                    $seller_product->stock -= $basket->quantity;
                    $seller_product->sale_count += 1;
                    $seller_product->save();
                }
            }

            return redirect()->route('success', $factor->id);
        }else{
            return redirect()->route('home')->with('err_message', 'پرداخت ناموفق');
        }
    }

    public function success($id)
    {
        $factor = Factor::findOrFail($id);
        $transaction = Transaction::where('factor_id', $factor->id)->latest()->first();

        return view('success', get_defined_vars());
    }

    public function add($id)
    {
        $item = ProductSeller::findOrFail($id);
        $product = $item->product;

        $unique = uniqid();
        if (!Cookie::has('user')) {
            Cookie::queue(Cookie::make('user', $unique, 518400));
        }
        $user = Cookie::get('user', $unique);

        $factor = Factor::where('user_session', $user)->whereStatus(0)->latest()->first();
        if (!$factor) {
            $factor = Factor::create([
                'user_id' => (Auth::check()?Auth::id():null),
                'user_session' => $user,
            ]);
        }

        $basket = Basket::where('factor_id', $factor->id)->where('seller_product_id', $item->id)->first();
        if ($basket){
            if (($basket->quantity + 1) <= $item->stock) {
                $basket->quantity += 1;
                $basket->price = ($item->price_off ? $item->price_off : $item->price) * $basket->quantity;
                $basket->save();
            }else{
                return redirect()->back()->with('err_message', 'موجودی محصول کافی نمی باشد');
            }
        }else {
            if ($item->stock) {
                $basket = Basket::create([
                    'factor_id' => $factor->id,
                    'product_id' => $product->id,
                    'seller_product_id' => $item->id,
                    'quantity' => 1,
                    'price' => ($item->price_off ? $item->price_off : $item->price),
                ]);
            }else{
                return redirect()->back()->with('err_message', 'موجودی محصول کافی نمی باشد');
            }
        }

        if ($factor and count($factor->baskets)){
            $total = 0;
            foreach ($factor->baskets as $basket){
                $total += $basket->price;
            }
            $factor->price = $total;
            $factor->save();
        }

        return redirect()->back()->with('flash_message', 'محصول به سبد خرید شما اضافه شد');
    }

    public function delete(Basket $basket)
    {
        try {
            $basket->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function change_quantity($id, $action)
    {
        $basket = Basket::find($id);
        $item = ProductSeller::find($basket->seller_product_id);
        $factor = Factor::find($basket->factor_id);

        if ($action == 'add'){
            if ($basket->quantity + 1 <= $item->stock){
                $basket->quantity += 1;
                $basket->price = ($item->price_off ? $item->price_off : $item->price) * $basket->quantity;
                $basket->save();

                if ($factor and count($factor->baskets)){
                    $total = 0;
                    foreach ($factor->baskets as $basket){
                        $total += $basket->price;
                    }
                    $factor->price = $total;
                    $factor->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'افزوده شد'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'موجودی کافی نیست'
                ]);
            }
        }else{
            if ($basket->quantity > 1){
                $basket->quantity -= 1;
                $basket->price = ($item->price_off ? $item->price_off : $item->price) * $basket->quantity;
                $basket->save();

                if ($factor and count($factor->baskets)){
                    $total = 0;
                    foreach ($factor->baskets as $basket){
                        $total += $basket->price;
                    }
                    $factor->price = $total;
                    $factor->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'کاهش یافت'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'تعداد سفارش نمی تواند کمتر از 1 باشد'
                ]);
            }
        }
    }

    public function add_address(Request $request)
    {
        try {
            $address = UserAddress::create([
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
            ]);

            return redirect()->back()->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function update_address(Request $request, $id)
    {
        $address = UserAddress::findOrFail($id);
        try {
            $address->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
            ]);

            return redirect()->back()->with('flash_message', 'بروزرسانی با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    public function delete_address($id)
    {
        try {
            $address = UserAddress::findOrFail($id);
            if ($address->user_id != Auth::id()){
                abort(403);
            }

            $address->delete();

            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
