<?php

namespace Modules\Shop\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\Shop\Database\factories\TransactionFactory::new();
    }

    public function factor()
    {
        return $this->belongsTo(Factor::class);
    }
}
