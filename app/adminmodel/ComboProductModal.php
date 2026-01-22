<?php

namespace App\adminmodel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboProductModal extends Model
{
    protected $table = 'combo_products';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'mrp', 'image',
        'is_active', 'sort_order', 'ip', 'added_by'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Products in this combo.
     */
    public function products()
    {
        return $this->belongsToMany(ProductModal::class, 'combo_product_items', 'combo_product_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
