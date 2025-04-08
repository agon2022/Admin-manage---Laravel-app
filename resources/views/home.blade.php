@extends('admin.layouts.master')

@section('content')
<div class="container">
    <h1>Dashboard!</h1>

    <div class="row">
        <!-- Sản phẩm -->
        <div class="col-md-3">
            <a href="{{ route('products.index') }}" class="card-link">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sản Phẩm</h5>
                        <p class="card-text">{{ $productCount }} sản phẩm</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Danh mục -->
        <div class="col-md-3">
            <a href="{{ route('category.index') }}" class="card-link">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Danh Mục</h5>
                        <p class="card-text">{{ $categoryCount }} danh mục</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Người dùng -->
        <div class="col-md-3">
            <a href="{{ route('users.index') }}" class="card-link">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Người Dùng</h5>
                        <p class="card-text">{{ $userCount }} người dùng</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Đặt hàng hôm nay -->
        <div class="col-md-3">
            <a href="{{ route('bookings.index') }}" class="card-link">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Tổng đơn hàng</h5>
                        <p class="card-text">{{ $bookingToday }} đơn hàng</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger mt-3">Đăng Xuất</a>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
@endsection

@section('styles')
<style>
    .card-link {
        text-decoration: none; /* Xóa gạch dưới */
    }

    .card:hover {
        transform: scale(1.05); /* Hiệu ứng phóng to khi hover */
        transition: all 0.3s ease-in-out; /* Thêm chuyển động mượt mà */
        cursor: pointer; /* Thay đổi con trỏ thành hình bàn tay */
    }

    .card:hover .card-title {
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Thêm bóng cho tiêu đề khi hover */
    }
</style>
@endsection
