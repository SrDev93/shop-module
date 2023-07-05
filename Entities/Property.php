<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\PropertyFactory::new();
    }

    public function products()
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }
}
