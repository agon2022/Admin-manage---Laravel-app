<?php

namespace App\Modules\Bookings\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Products\app\Models\Product;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity', 'status', 'booking_date'];

    protected $casts = [
        'booking_date' => 'date',
    ];

    // Liên kết với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Liên kết với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->product ? $this->product->price * $this->quantity : 0;
    }
}
