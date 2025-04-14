<?php

namespace App\Modules\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Products\app\Models\Product;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'booking_date'];

    protected $casts = [
        'booking_date' => 'date',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ nhiều sản phẩm (many-to-many)
    // App\Modules\Bookings\app\Models\Booking.php
    public function products()
    {
        return $this->belongsToMany(\App\Modules\Products\app\Models\Product::class)
            ->withPivot('quantity', 'updated_at')
            ->withTimestamps();
    }


    // Tổng giá
    public function getTotalPriceAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });
    }
}
