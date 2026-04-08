<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'sort_order',
        'meta_title', 'meta_description', 'ip', 'added_by', 'is_active'
    ];

    protected $dates = ['deleted_at'];

    // Relationship with Subcategories
    public function subcategories()
    {
        return $this->hasMany(\App\adminmodel\SubcategoryModal::class, 'category_id');
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(\App\adminmodel\ProductModal::class, 'category_id');
    }
}