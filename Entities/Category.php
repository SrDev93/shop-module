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
        return $this->hasMany(Product::class);
    }

}
