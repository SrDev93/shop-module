<?php

namespace Modules\Shop\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\User;
use Modules\Base\Entities\Comment;
use Modules\Base\Entities\Photo;
use Modules\Base\Entities\Visit;

class Product extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\ProductFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sellers()
    {
        return $this->hasMany(ProductSeller::class);
    }

    public function getPriceAttribute()
    {
        if ($this->sellers()->whereNotNull('price_off')->first()){
            return $this->sellers()->whereNotNull('price_off')->orderBy('price_off', 'ASC')->first();

//            $item = $this->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
//                ->select('products.*', DB::raw('min(product_sellers.price_off) as min_product_price'))
//                ->groupBy('products.id')
//                ->orderBy('min_product_price', 'asc')
//                ->first();
//
//            return $item->min_product_price;
        }else{
            return $this->sellers()->orderBy('price', 'ASC')->first();
//            $item = $this->leftJoin('product_sellers', 'products.id', '=', 'product_sellers.product_id')
//                ->select('products.*', DB::raw('min(product_sellers.price) as min_product_price'))
//                ->groupBy('products.id')
//                ->orderBy('min_product_price', 'asc')
//                ->first();
//
//            return $item->min_product_price;
        }
    }

    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function tags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'comments')->whereNull('parent_id')->where('status', 1);
    }

    public function visits() {
        return $this->morphMany(Visit::class, 'visits');
    }

    public function photo() {
        return $this->morphMany(Photo::class, 'pictures');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
