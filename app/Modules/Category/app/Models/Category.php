<?php

namespace app\Modules\Category\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\app\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'content',
        'parent_id',
        'active',
        'arrange'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
