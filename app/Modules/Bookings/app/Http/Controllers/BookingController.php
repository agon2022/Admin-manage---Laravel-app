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
        $search     = $request->input('search');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $bookings = Booking::when($search, function ($query, $search) {
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('booking_date', [$startDate, $endDate]);
            })
            ->with([
                'user',
                'products' => function ($q) {
                    $q->withPivot('quantity', 'updated_at');
                }
            ])
            ->orderByDesc('id')
            ->paginate(5)
            ->appends($request->query());

        $users = User::all();

        return view('Bookings::index', compact('bookings', 'users'));
    }

    public function create()
    {
        $users    = User::all();
        $products = Product::all();

        return view('Bookings::create', compact('users', 'products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'booking_date'        => 'nullable|date',
            'status'              => 'required|in:pending,confirmed,canceled',
            'products'            => 'required|array|min:1',
            'products.*.id'       => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $booking = Booking::create([
            'user_id'      => $request->user_id,
            'booking_date' => $request->booking_date ?? now(),
            'status'       => $request->status,
        ]);

        foreach ($request->products as $product) {
            $booking->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('bookings.index')->with('success', 'Đơn hàng đã được tạo thành công.');
    }

    public function edit($id)
    {
        $booking  = Booking::with(['products'])->findOrFail($id);
        $users    = User::all();
        $products = Product::all();

        return view('Bookings::edit', compact('booking', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'booking_date'        => 'nullable|date',
            'status'              => 'required|in:pending,confirmed,canceled',
            'products'            => 'required|array',
            'products.*.id'       => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'user_id'      => $request->user_id,
            'status'       => $request->status,
        ]);

        $syncData = [];
        foreach ($request->products as $product) {
            $syncData[$product['id']] = [
                'quantity' => $product['quantity'],
                'updated_at' => now(),
            ];
        }

        $booking->products()->sync($syncData);

        return redirect()->route('bookings.index')->with('success', 'Cập nhật đơn hàng thành công.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Xóa đơn hàng thành công.');
    }
}
