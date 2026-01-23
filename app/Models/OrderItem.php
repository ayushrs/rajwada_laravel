<?php

namespace App\Models;

use App\adminmodel\ProductModal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

     public function product()
    {
        return $this->belongsTo(ProductModal::class);
    }
}
