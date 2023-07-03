<?php

namespace Modules\Shop\Http\Controllers\Front;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Modules\Shop\Entities\Product;
use Modules\Shop\Entities\Wishlist;

class WishlistController extends Controller
{
    public function add(Product $product)
    {
        $unique = uniqid();
        if (!Cookie::has('user')) {
            Cookie::queue(Cookie::make('user', $unique, 518400));
        }

        $user = Cookie::get('user', $unique);

        $old = Wishlist::where('user_session', $user)->where('product_id', $product->id)->first();
        if ($old) {
            return redirect()->back()->with('err_message', 'محصول در لیست علاقه مندی های شما وجود دارد');
        }

        $item = Wishlist::create([
            'user_id' => (Auth::check()?Auth::id():null),
            'user_session' => $user,
            'product_id' => $product->id,
        ]);

        return redirect()->back()->with('flash_message', 'محصول به لیست علاقه مندی شما اضافه شد');
   }

    public function delete(Wishlist $wishlist)
    {
        try {
            $wishlist->delete();
            return redirect()->back()->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
   }
}
