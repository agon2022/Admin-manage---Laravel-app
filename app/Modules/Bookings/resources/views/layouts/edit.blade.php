@extends('admin.layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Booking</h3>
        </div>
        <!-- Hiển thị thông báo -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="card-body">
            <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Chọn User -->
                <div class="mb-3">
                    <label class="form-label">User:</label>
                    <select name="user_id" class="form-control" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Chọn Product -->
                <div class="mb-3">
                    <label class="form-label">Product:</label>
                    <select name="product_id" class="form-control" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $booking->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nhập Số lượng -->
                <div class="mb-3">
                    <label class="form-label">Quantity:</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $booking->quantity }}" required>
                </div>

                <!-- Chọn Ngày đặt -->
                <div class="mb-3">
                    <label class="form-label">Booking Date:</label>
                    <input type="date" name="booking_date" class="form-control"
       value="{{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }}" required>
                </div>

                <!-- Trạng thái Booking -->
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="canceled" {{ $booking->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <!-- Nút Submit -->
                <button type="submit" class="btn btn-success">Update Booking</button>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
