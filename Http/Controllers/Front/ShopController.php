<?php

namespace Modules\Shop\Http\Controllers\Front;

use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Modules\Base\Entities\Setting;
use Modules\Base\Entities\Visit;
use Modules\IrCity\Entities\ProvinceCity;
use Modules\Shop\Entities\Basket;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\Factor;
use Modules\Shop\Entities\Product;
use Modules\Shop\Entities\ProductProperty;
use Modules\Shop\Entities\ProductSeller;
use Modules\Shop\Entities\Transaction;
use Modules\Shop\Entities\UserAddress;

class ShopController extends Controller
{
    protected $per_page = 20;


    // shop view methods

    public function index_all()
    {
        $rating = [0,1,2,3,4,5];
        if (isset($_GET['rating'])){
            $rating = $_GET['rating'];
        }

        if (isset($_GET['categories'])){
            $cat_filters = $_GET['categories'];
        }else{
            $cat_filters = Category::pluck('id')->toArray();
        }

        if (isset($_GET['min_price']) and isset($_GET['max_price'])){
            $min_price = (int)$_GET['min_price'];
            $max_price = (int)$_GET['max_price'];
        }else{
            $min_price = 0;
            $max_price = ProductSeller::max('price');
        }

        $product_sellers = ProductSeller::whereBetween('price', [$min_price, $max_price])->orWhereBetween('price_off', [$min_price, $max_price])->pluck('product_id')->toArray();

        $sort = 'newest';
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if ($sort == 'popular'){
            $items = Product::whereStatus(1)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->orderBy('visit', 'DESC')->paginate($this->per_page);
        }elseif ($sort == 'price_asc'){


            $items = Product::where('products.status',1)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('min_product_price', 'asc')
                ->paginate($this->per_page);


        }elseif ($sort == 'price_desc'){

            $items = Product::where('products.status',1)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('max_product_price', 'desc')
                ->paginate($this->per_page);

        }else{ // newest
            $items = Product::whereStatus(1)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->latest()->paginate($this->per_page);
        }

        $banner = Banner::wherePage('shop')->wherePosition('sidebar')->first();
        $categories = Category::whereNull('parent_id')->get();

        $per_page = $this->per_page;
        $page = (int)(isset($_GET['page'])?$_GET['page']:'1');
        $total = Product::whereStatus(1)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->count();
        $view = $per_page * $page;
        if ($view > $total){
            $view = $total;
        }

//        $five_star = Product::where('rating','>=',5)->count();
//        $four_star = Product::where('rating','>=',4)->where('rating','<',5)->count();
//        $three_star = Product::where('rating','>=',3)->where('rating','<',4)->count();
//        $two_star = Product::where('rating','>=',2)->where('rating','<',3)->count();
//        $one_star = Product::where('rating','>=',1)->where('rating','<',2)->count();

        return view('shop.index', get_defined_vars());
    }

    public function index($slug)
    {
        $category = Category::whereSlug($slug)->firstOrFail();

        if (isset($_GET['categories'])){
            $cat_filters = $_GET['categories'];
        }else{
            $cat_filters = Category::pluck('id')->toArray();
        }

        $rating = [0,1,2,3,4,5];
        if (isset($_GET['rating'])){
            $rating = $_GET['rating'];
        }

        if (isset($_GET['min_price']) and isset($_GET['max_price'])){
            $min_price = (int)$_GET['min_price'];
            $max_price = (int)$_GET['max_price'];
        }else{
            $min_price = 0;
            $max_price = ProductSeller::max('price');
        }

        $product_sellers = ProductSeller::whereBetween('price', [$min_price, $max_price])->orWhereBetween('price_off', [$min_price, $max_price])->pluck('product_id')->toArray();

        if (isset($_GET['properties'])){
            $properties = $_GET['properties'];
            $product_properties = ProductProperty::whereIn('id', $properties)->pluck('product_id')->toArray();
            $prop_products = Product::whereStatus(1)->whereIn('id', $product_properties)->pluck('id')->toArray();
        }else{
            $prop_products = Product::whereStatus(1)->where('category_id', $category->id)->pluck('id')->toArray();
        }

        $filtered_array = [];
        foreach ($prop_products as $prop_product){
            if (in_array($prop_product, $product_sellers)){
                array_push($filtered_array, $prop_product);
            }
        }


        $sort = 'newest';
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if ($sort == 'popular'){
            $items = Product::whereStatus(1)->where('category_id', $category->id)->whereIn('id', $filtered_array)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->orderBy('visit', 'DESC')->paginate($this->per_page);
        }elseif ($sort == 'price_asc'){


            $items = Product::where('products.status',1)->where('category_id', $category->id)->whereIn('products.id', $filtered_array)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('min_product_price', 'asc')
                ->paginate($this->per_page);


        }elseif ($sort == 'price_desc'){

            $items = Product::where('products.status',1)->where('category_id', $category->id)->whereIn('products.id', $filtered_array)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('max_product_price', 'desc')
                ->paginate($this->per_page);

        }else{ // newest
            $items = Product::whereStatus(1)->where('category_id', $category->id)->whereIn('id', $filtered_array)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->latest()->paginate($this->per_page);
        }

        $banner = Banner::wherePage('shop')->wherePosition('sidebar')->first();
        $categories = Category::whereNull('parent_id')->get();

        $per_page = $this->per_page;
        $page = (int)(isset($_GET['page'])?$_GET['page']:'1');
        $total = Product::whereStatus(1)->where('category_id', $category->id)->whereIn('id', $filtered_array)->whereIn('rating', $rating)->whereIn('category_id', $cat_filters)->count();
        $view = $per_page * $page;
        if ($view > $total){
            $view = $total;
        }

//        $five_star = Product::where('category_id', $category->id)->where('rating','>=',5)->count();
//        $four_star = Product::where('category_id', $category->id)->where('rating','>=',4)->where('rating','<',5)->count();
//        $three_star = Product::where('category_id', $category->id)->where('rating','>=',3)->where('rating','<',4)->count();
//        $two_star = Product::where('category_id', $category->id)->where('rating','>=',2)->where('rating','<',3)->count();
//        $one_star = Product::where('category_id', $category->id)->where('rating','>=',1)->where('rating','<',2)->count();

        return view('shop.index', get_defined_vars());
    }

    public function show($slug)
    {
        $item = Product::whereSlug($slug)->firstOrFail();

        $relateds = Product::where('category_id', $item->category_id)->latest()->take(10)->get();


        $ip = \Request::ip();
        $visit = Visit::where('ip', $ip)->where('visits_type', 'Modules\Blogs\Entities\Blog')->where('visits_id', $item->id)->whereDate('created_at', Carbon::today())->first();
        if (!$visit) {
            $vis = new Visit();
            $vis->ip = $ip;
            $item->visits()->save($vis);

            $item->visit += 1;
            $item->save();
        }

        return view('shop.show', get_defined_vars());
    }

    public function quick_view($slug)
    {
        $item = Product::whereSlug($slug)->firstOrFail();

        return response()->json(view('partials.product_quick_view', get_defined_vars())->render());
    }

    public function festival()
    {
        $sort = 'newest';
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }

        if ($sort == 'popular'){
            $items = Product::whereStatus(1)->whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->orderBy('visit', 'DESC')->paginate($this->per_page);
        }elseif ($sort == 'price_asc'){


            $items = Product::where('products.status',1)->whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('min_product_price', 'asc')
                ->paginate($this->per_page);


        }elseif ($sort == 'price_desc'){

            $items = Product::where('products.status',1)->whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('max_product_price', 'desc')
                ->paginate($this->per_page);

        }else{ // newest
            $items = Product::whereStatus(1)->whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->latest()->paginate($this->per_page);
        }

        $banner = Banner::wherePage('shop')->wherePosition('sidebar')->first();
        $categories = Category::whereNull('parent_id')->get();

        $per_page = $this->per_page;
        $page = (int)(isset($_GET['page'])?$_GET['page']:'1');
        $total = Product::whereStatus(1)->whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->count();
        $view = $per_page * $page;
        if ($view > $total){
            $view = $total;
        }

//        $five_star = Product::whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->where('rating','>=',5)->count();
//        $four_star = Product::whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->where('rating','>=',4)->where('rating','<',5)->count();
//        $three_star = Product::whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->where('rating','>=',3)->where('rating','<',4)->count();
//        $two_star = Product::whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->where('rating','>=',2)->where('rating','<',3)->count();
//        $one_star = Product::whereHas('sellers', function ($query){$query->whereNotNull('price_off');})->where('rating','>=',1)->where('rating','<',2)->count();

        return view('shop.index', get_defined_vars());
    }

    public function search(Request $request)
    {
        if (isset($request->q)){
            $query = $request->q;
        }else{
            $query = null;
        }

        if (isset($_GET['categories'])){
            $cat_filters = $_GET['categories'];
        }else{
            $cat_filters = Category::pluck('id')->toArray();
        }

        if (isset($_GET['min_price']) and isset($_GET['max_price'])){
            $min_price = (int)$_GET['min_price'];
            $max_price = (int)$_GET['max_price'];
        }else{
            $min_price = 0;
            $max_price = ProductSeller::max('price');
        }

        $product_sellers = ProductSeller::whereBetween('price', [$min_price, $max_price])->orWhereBetween('price_off', [$min_price, $max_price])->pluck('product_id')->toArray();

        $sort = 'newest';
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if ($sort == 'popular'){
            $items = Product::whereStatus(1)->where('name', 'LIKE', '%'.$query.'%')->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->orderBy('visit', 'DESC')->paginate($this->per_page);
        }elseif ($sort == 'price_asc'){


            $items = Product::where('products.status',1)->where('name', 'LIKE', '%'.$query.'%')->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('min_product_price', 'asc')
                ->paginate($this->per_page);


        }elseif ($sort == 'price_desc'){

            $items = Product::where('products.status',1)->where('name', 'LIKE', '%'.$query.'%')->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('max_product_price', 'desc')
                ->paginate($this->per_page);

        }else{ // newest
            $items = Product::whereStatus(1)->where('name', 'LIKE', '%'.$query.'%')->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->latest()->paginate($this->per_page);
        }

        $banner = Banner::wherePage('shop')->wherePosition('sidebar')->first();
        $categories = Category::whereNull('parent_id')->get();

        $per_page = $this->per_page;
        $page = (int)(isset($_GET['page'])?$_GET['page']:'1');
        $total = Product::whereStatus(1)->where('name', 'LIKE', '%'.$query.'%')->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->count();
        $view = $per_page * $page;
        if ($view > $total){
            $view = $total;
        }

        return view('shop.search', get_defined_vars());
    }

    public function amazing_sales()
    {
        if (isset($_GET['categories'])){
            $cat_filters = $_GET['categories'];
        }else{
            $cat_filters = Category::pluck('id')->toArray();
        }

        if (isset($_GET['min_price']) and isset($_GET['max_price'])){
            $min_price = (int)$_GET['min_price'];
            $max_price = (int)$_GET['max_price'];
        }else{
            $min_price = 0;
            $max_price = ProductSeller::max('price');
        }

        $product_sellers = ProductSeller::whereNotNull('amazing_date')->whereBetween('price', [$min_price, $max_price])->orWhereBetween('price_off', [$min_price, $max_price])->pluck('product_id')->toArray();

        $sort = 'newest';
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        if ($sort == 'popular'){
            $items = Product::whereStatus(1)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->orderBy('visit', 'DESC')->paginate($this->per_page);
        }elseif ($sort == 'price_asc'){


            $items = Product::where('products.status',1)->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('min_product_price', 'asc')
                ->paginate($this->per_page);


        }elseif ($sort == 'price_desc'){

            $items = Product::where('products.status',1)->whereIn('category_id', $cat_filters)->whereIn('products.id', $product_sellers)->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'), DB::raw('max(product_sellers.price) as max_product_price'))
                ->groupBy('products.id')
                ->orderBy('max_product_price', 'desc')
                ->paginate($this->per_page);

        }else{ // newest
            $items = Product::whereStatus(1)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->latest()->paginate($this->per_page);
        }

        $banner = Banner::wherePage('shop')->wherePosition('sidebar')->first();
        $categories = Category::whereNull('parent_id')->get();

        $per_page = $this->per_page;
        $page = (int)(isset($_GET['page'])?$_GET['page']:'1');
        $total = Product::whereStatus(1)->whereIn('category_id', $cat_filters)->whereIn('id', $product_sellers)->count();
        $view = $per_page * $page;
        if ($view > $total){
            $view = $total;
        }

        return view('shop.vip', get_defined_vars());
    }



    // shop action methods

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

        $basket_array = [];
        foreach ($factor->baskets as $basket){
            if($basket->seller_product->seller_id){
                if (!in_array($basket->seller_product->seller_id, $basket_array)){
                    array_push($basket_array, $basket->seller_product->seller_id);
                }
            }
        }

        $setting = Setting::first();
        $uniq_shipping_cost = $setting->shipping_cost?$setting->shipping_cost:20000;
        $shipping_cost = $uniq_shipping_cost * count($basket_array);

        $factor->shipping_cost = $shipping_cost;
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
