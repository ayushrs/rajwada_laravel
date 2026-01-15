<?php

namespace App\adminmodel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubcategoryModal extends Model
{
    protected $table = 'subcategories';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image', 'sort_order', 
        'meta_title', 'meta_description', 'ip', 'added_by', 'is_active'
    ];
    
    use SoftDeletes;
    protected $del = ['deleted_at'];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(CategoryModal::class, 'category_id');
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(ProductModal::class, 'subcategory_id');
    }
}
