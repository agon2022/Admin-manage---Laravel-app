<?php

namespace App\Modules\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Modules\Products\app\Models\Product;
use App\Modules\Bookings\app\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $bookings = Booking::when($search, function ($query, $search) {
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('booking_date', [$startDate, $endDate]);
            })
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(7);

        $users = User::all(); // Thêm dòng này để lấy danh sách user

        return view('Bookings::index', compact('bookings', 'users')); // Truyền $users vào view
    }


    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('Bookings::create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'booking_date' => 'nullable|date',
            'status' => 'required|string'
        ]);

        Booking::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'booking_date' => $request->booking_date, // Giữ nguyên không đổi
            'status' => $request->status,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Đã đặt đơn hàng thành công.');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $users = User::all();
        $products = Product::all();
        return view('Bookings::edit', compact('booking', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'booking_date' => 'nullable|date', // Chỉ nhận ngày hợp lệ
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        // Tìm booking cần cập nhật
        $booking = Booking::findOrFail($id);

        // Cập nhật giá trị booking
        $booking->user_id = $request->user_id;
        $booking->product_id = $request->product_id;
        $booking->quantity = $request->quantity;
        $booking->status = $request->status;

        // Nếu không nhập ngày thì lấy ngày hiện tại
        $booking->booking_date = $request->booking_date ? Carbon::parse($request->booking_date)->format('Y-m-d') : Carbon::now()->toDateString();

        $booking->save();

        return redirect()->route('bookings.index')->with('success', 'Cập nhật đơn hàng thành công.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Xóa đơn hàng thành công.');
    }
}
