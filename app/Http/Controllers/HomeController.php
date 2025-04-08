<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Modules\Products\app\Models\Product;
use App\Modules\Category\app\Models\Category;
use App\Modules\Bookings\app\Models\Booking;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userCount = User::count(); // Count total users
        $productCount = Product::count(); // Count total products
        $categoryCount = Category::count(); // Count total categories
        $bookingCount = Booking::count(); // Count total bookings

        $bookingToday = Booking::count(); // Count today's bookings

        return view('home', compact('userCount', 'productCount', 'categoryCount', 'bookingCount', 'bookingToday'));
    }
}
