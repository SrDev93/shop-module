<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\User;
use Modules\Base\Entities\Comment;
use Modules\Base\Entities\Photo;
use Modules\Base\Entities\Visit;

class Product extends Model
{
    use HasFactory;

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
        return $this->morphMany(Comment::class, 'comments')->where('status', 1);
    }

    public function visits() {
        return $this->morphMany(Visit::class, 'visits');
    }

    public function photo() {
        return $this->morphMany(Photo::class, 'pictures');
    }
}
