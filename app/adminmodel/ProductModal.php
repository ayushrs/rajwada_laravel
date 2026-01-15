<?php

namespace App\adminmodel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModal extends Model
{
    protected $table = 'products';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'sku', 'slug', 'short_description', 
        'description', 'mrp', 'price', 'selling_price', 'gst_percentage', 'gst', 
        'image', 'image2', 'image3', 'image4', 'stock_quantity', 'size', 'color', 
        'material', 'brand', 'is_top', 'is_featured', 'is_new_arrival', 
        'meta_title', 'meta_description', 'meta_keywords', 'ip', 'added_by', 'is_active'
    ];
    
    use SoftDeletes;
    protected $del = ['deleted_at'];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(CategoryModal::class, 'category_id');
    }

    // Relationship with Subcategory
    public function subcategory()
    {
        return $this->belongsTo(SubcategoryModal::class, 'subcategory_id');
    }
}
