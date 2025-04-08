<?php

namespace App\Modules\ProductGallery\app\Models; // Kiểm tra namespace

use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\app\Models\Product;

class ProductGallery extends Model
{
    protected $table = 'products_gallery';
    protected $fillable = ['product_id', 'image_path'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
