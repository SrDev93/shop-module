<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Entities\Basket;
use Modules\Shop\Entities\Factor;
use Modules\Shop\Entities\ProductSeller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->hasrole('seller')){
            if (!Auth::user()->seller){
                return redirect()->back()->with('err_message', 'لطفا پروفایل خود را تکمیل نمایید');
            }
            $product_sellers = ProductSeller::where('seller_id', Auth::user()->seller->id)->pluck('id')->toArray();
            $baskets = Basket::whereIn('seller_product_id', $product_sellers)->pluck('factor_id')->toArray();
            $items = Factor::whereIn('id', $baskets)->where('status', '!=', 0)->get();
        }else {
            $items = Factor::where('status', '!=', 0)->get();
        }

        return view('shop::orders.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('shop::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Factor $order)
    {
        $item = $order;

        if (Auth::user()->hasrole('seller')){
            if (!Auth::user()->seller){
                return redirect()->back()->with('err_message', 'لطفا پروفایل خود را تکمیل نمایید');
            }
            $product_sellers = ProductSeller::where('seller_id', Auth::user()->seller->id)->pluck('id')->toArray();
            $baskets = Basket::where('factor_id', $item->id)->whereIn('seller_product_id', $product_sellers)->pluck('id')->toArray();
        }else{
            $baskets = $item->baskets()->pluck('id')->toArray();
        }

        return view('shop::orders.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('shop::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function status(Factor $factor)
    {
        try {
            if ($factor->status < 4) {
                $factor->status += 1;
                $factor->save();
            }

            return redirect()->back()->with('flash_message', 'با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }
}
