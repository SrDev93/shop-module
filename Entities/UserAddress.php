<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\User;

class UserAddress extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\UserAddressFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factor()
    {
        return $this->hasOne(Factor::class);
    }
}
