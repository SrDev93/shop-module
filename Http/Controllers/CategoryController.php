<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Shop\Entities\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->orderBy('sort_id')->get();

        return view('shop::category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('shop::category.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $ac = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'photo' => (isset($request->photo)?file_store($request->photo, 'assets/uploads/photos/category_photo/', 'photo_'):null),
                'banner' => (isset($request->banner)?file_store($request->banner, 'assets/uploads/photos/category_banner/', 'photo_'):null),
            ]);

            return redirect()->route('category.index')->with('flash_message', 'با موفقیت ثبت شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('shop::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Category $category)
    {
        return view('shop::category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Category $category)
    {
        try {
            $category->name = $request->name;
            $category->slug = $request->slug;
            if (isset($request->photo)) {
                if ($category->photo){
                    File::delete($category->photo);
                }
                $category->photo = file_store($request->photo, 'assets/uploads/photos/category_photo/', 'photo_');
            }
            if (isset($request->banner)) {
                if ($category->banner){
                    File::delete($category->banner);
                }
                $category->banner = file_store($request->banner, 'assets/uploads/photos/category_banner/', 'photo_');
            }
            $category->save();

            return redirect()->route('category.index')->with('flash_message', 'بروزرسانی با موفقیت انجام شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('category.index')->with('flash_message', 'با موفقیت حذف شد');
        }catch (\Exception $e){
            return redirect()->back()->with('err_message', 'خطایی رخ داده است، لطفا مجددا تلاش نمایید');
        }
    }

    /**
     * Sort Item.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function sort_item(Request $request)
    {
        $category_item_sort = json_decode($request->sort);
        $this->sort_category($category_item_sort, null);
    }

    /**
     * Sort Category.
     *
     *
     * @param $category_items
     * @param $parent_id
     */
    private function sort_category(array $category_items, $parent_id)
    {
        foreach ($category_items as $index => $category_item) {
            $item = Category::findOrFail($category_item->id);
            $item->sort_id = $index + 1;
            $item->parent_id = $parent_id;
            $item->save();
            if (isset($category_item->children)) {
                $this->sort_category($category_item->children, $item->id);
            }
        }
    }
}
