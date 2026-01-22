<?php

namespace App\adminmodel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionModal extends Model
{
    protected $table = 'collections';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'sort_order',
        'meta_title', 'meta_description', 'ip', 'added_by', 'is_active'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Products in this collection.
     */
    public function products()
    {
        return $this->belongsToMany(ProductModal::class, 'collection_products', 'collection_id', 'product_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('collection_products.sort_order');
    }
}
