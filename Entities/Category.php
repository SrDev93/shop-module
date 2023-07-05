<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\CategoryFactory::new();
    }

    public function parent()
    {
        return $this->hasOne(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->whereStatus(1);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function TotalProducts($filter= 'newest', $limit = 10)
    {
        $categories = [];
        array_push($categories, $this->id);
        foreach ($this->children as $child){
            array_push($categories, $child->id);
            foreach ($child->children as $child2){
                array_push($categories, $child2->id);
            }
        }

        if ($filter == 'most_visited'){
            return Product::whereStatus(1)->whereIn('category_id', $categories)->orderBy('visit', 'desc')->take($limit)->get();
        }
        elseif ($filter == 'popolar'){
            return Product::whereStatus(1)->whereIn('category_id', $categories)->orderBy('rating', 'desc')->take($limit)->get();
        }
        elseif ($filter == 'discounted'){
            return Product::whereStatus(1)->whereIn('category_id', $categories)->whereHas('sellers', function ($query) {
                $query->whereNotNull('price_off');
            })->latest()->take($limit)->get();
        }
        else {
            return Product::whereStatus(1)->whereIn('category_id', $categories)->latest()->take($limit)->get();
        }
    }
}
