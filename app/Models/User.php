<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Models\Bookings;
use App\Modules\Bookings\app\Models\Booking;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use Authorizable;
    protected $fillable = ['name', 'email', 'password', 'avatar'];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
