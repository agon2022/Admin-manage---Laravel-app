@extends('admin.layouts.master')

@section('admin-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách Booking</h3>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary float-right">Add booking</a>
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
        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('bookings.index') }}" class="mb-3">
            <div class="row">
                <!-- Tìm kiếm theo tên User -->
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc email" value="{{ request('search') }}">
                </div>

                <!-- Chọn ngày bắt đầu -->
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <!-- Chọn ngày kết thúc -->
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <!-- Nút tìm kiếm -->
                <div class="col-md-2 d-flex justify-content-end">
                    <button type="submit" class="btn btn-info">Lọc</button>
                </div>
            </div>
        </form>

        <!-- Bảng danh sách booking -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $bookings->firstItem() + $loop->index }}</td>
                        <td>{{ $booking->user->name ?? 'Không có user' }}</td>
                        <td>{{ $booking->user->email ?? 'Không có email' }}</td>
                        <td>{{ $booking->product->name }}</td>
                        <td>{{ $booking->quantity ?? 1 }}</td> <!-- Thêm cột số lượng -->
                        <td>{{ number_format($booking->total_price ?? 0, 0, ',', '.') }} VNĐ</td> <!-- Hiển thị tổng tiền -->
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('bookings.edit', $booking->id) }}">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đơn đặt hàng này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>            
        </table>

        <!-- Hiển thị phân trang -->
        <div class="d-flex justify-content-center mt-3">
            {{ $bookings->links('pagination::bootstrap-4') }}
        </div>        
    </div>
</div>
@endsection
