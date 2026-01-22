<?php

namespace App\adminmodel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ReviewModal extends Model
{
    protected $table = 'reviews';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id', 'user_id', 'customer_name', 'customer_email',
        'rating', 'comment', 'is_approved', 'ip'
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo(ProductModal::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
