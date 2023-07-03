<?php

namespace Modules\Shop\Http\Controllers\Front;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Modules\Shop\Entities\Product;

class CompareController extends Controller
{
    public function add(Product $product)
    {
        if (!Cookie::has('compare')) {
            $list = [$product->id];
            Cookie::queue(Cookie::make('compare', serialize($list), 51840));

            return redirect()->route('compare')->with('flash_message', 'محصول به لیست مقایسه افزوده شد');
        }else {

            $compare = Cookie::get('compare');
            $compare = unserialize($compare);
            if (in_array($product->id, $compare)){
                return redirect()->route('compare')->with('err_message', 'محصول در لیست مقایسه وجود دارد');
            }
            if (count($compare) >= 3){
                return redirect()->route('compare')->with('err_message', 'امکان مقایسه بیش از 3 محصول با هم وجود ندارد');
            }
            if (count($compare)) {
                $exist_product = Product::find($compare[0]);
                if ($exist_product->category_id != $product->category_id) {
                    return redirect()->route('compare')->with('err_message', 'امکان مقایسه محصولات غیر هم نوع وجود ندارد');
                }
            }

            array_push($compare, $product->id);

            Cookie::queue(Cookie::make('compare', serialize($compare), 51840));

            return redirect()->route('compare')->with('flash_message', 'محصول به لیست مقایسه افزوده شد');
        }
    }

    public function delete(Product $product)
    {
        if (!Cookie::has('compare')) {
            return redirect()->route('compare')->with('flash_message', 'محصول به لیست مقایسه افزوده شد');
        }else {

            $compare = Cookie::get('compare');
            $compare = unserialize($compare);

            if (($key = array_search($product->id, $compare)) !== false) {
                unset($compare[$key]);
            }

            Cookie::queue(Cookie::make('compare', serialize($compare), 51840));

            return redirect()->route('compare')->with('flash_message', 'محصول از لیست مقایسه حذف شد');
        }
    }
}
