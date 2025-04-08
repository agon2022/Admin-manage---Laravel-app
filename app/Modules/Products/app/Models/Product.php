<?php

namespace App\Modules\Products\app\Models;

use App\Modules\Category\app\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\ProductGallery\app\Models\ProductGallery;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'category_id', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function gallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_id'); // 🛠 Kiểm tra đúng tên model
    }
}
