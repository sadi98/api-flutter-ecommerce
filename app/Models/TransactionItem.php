<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product()
    {
        return $this->hasOne(Product::class,'id','products_id');
    }
}
