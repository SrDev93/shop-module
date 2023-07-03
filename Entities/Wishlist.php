<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\WishlistFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
