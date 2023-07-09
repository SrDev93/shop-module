<?php

namespace Modules\Shop\Http\Controllers;

use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Entities\Basket;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from_date = null;
        $to_date = null;
        if ($request->has('from_date') or $request->has('to_date')){
            if (!$request->from_date || !$request->to_date) {
                return redirect()->back()->with('err_message', 'لطفا تاریخ شروع و پایان را برای گزارش گیری انتخاب کنید');
            }

            $from_date = Verta::parse(($request->get('from_date')))->datetime()->format('Y-m-d');
            $to_date = Verta::parse(($request->get('to_date')))->datetime()->format('Y-m-d');
        }
        if (Auth::user()->hasrole('seller')) {

            if (!Auth::user()->seller) {
                return redirect()->back()->with('err_message', 'لطفا پروفایل خود را تکمیل نمایید');
            }


            $seller = Auth::user()->seller;

            if ($from_date and $to_date){
                $items = Basket::has('product')
                    ->whereHas('seller_product', function ($q) use ($seller) {
                        $q->where('seller_id', $seller->id);
                    })->whereHas('factor', function ($query) {
                        $query->where('status', '!=', 0);
                    })->whereDate('created_at', '>=',$from_date)->whereDate('created_at', '<=',$to_date)
                    ->get();
            }else {
                $items = Basket::has('product')
                    ->whereHas('seller_product', function ($q) use ($seller) {
                        $q->where('seller_id', $seller->id);
                    })->whereHas('factor', function ($query) {
                        $query->where('status', '!=', 0);
                    })->get();
            }

        } else {

            if ($from_date and $to_date) {
                $items = Basket::has('product')->whereHas('factor', function ($query) {
                    $query->where('status', '!=', 0);
                })->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            }else{
                $items = Basket::has('product')->whereHas('factor', function ($query) {
                    $query->where('status', '!=', 0);
                })->get();
            }

        }

        return view('shop::report.sales', get_defined_vars());
    }

    public function financial(Request $request)
    {
        $from_date = null;
        $to_date = null;
        if ($request->has('from_date') or $request->has('to_date')){
            if (!$request->from_date || !$request->to_date) {
                return redirect()->back()->with('err_message', 'لطفا تاریخ شروع و پایان را برای گزارش گیری انتخاب کنید');
            }

            $from_date = Verta::parse(($request->get('from_date')))->datetime()->format('Y-m-d');
            $to_date = Verta::parse(($request->get('to_date')))->datetime()->format('Y-m-d');
        }
        if (Auth::user()->hasrole('seller')) {

            if (!Auth::user()->seller) {
                return redirect()->back()->with('err_message', 'لطفا پروفایل خود را تکمیل نمایید');
            }


            $seller = Auth::user()->seller;

            if ($from_date and $to_date){
                $items = Basket::has('product')
                    ->whereHas('seller_product', function ($q) use ($seller) {
                        $q->where('seller_id', $seller->id);
                    })->whereHas('factor', function ($query) {
                        $query->where('status', '!=', 0);
                    })->whereDate('created_at', '>=',$from_date)->whereDate('created_at', '<=',$to_date)
                    ->get();
            }else {
                $items = Basket::has('product')
                    ->whereHas('seller_product', function ($q) use ($seller) {
                        $q->where('seller_id', $seller->id);
                    })->whereHas('factor', function ($query) {
                        $query->where('status', '!=', 0);
                    })->get();
            }

        } else {

            if ($from_date and $to_date) {
                $items = Basket::has('product')->whereHas('factor', function ($query) {
                    $query->where('status', '!=', 0);
                })->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            }else{
                $items = Basket::has('product')->whereHas('factor', function ($query) {
                    $query->where('status', '!=', 0);
                })->get();
            }

        }

        return view('shop::report.financial', get_defined_vars());
    }
}
