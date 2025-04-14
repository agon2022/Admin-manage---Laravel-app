@extends('admin.layouts.master')

@section('admin-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Chỉnh Sửa User</h3>
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
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu (để trống nếu không đổi):</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu:</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>            

            <!-- Chọn Role -->
            <div class="form-group">
                <label>Chọn Role</label>
                <select name="roles[]" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>            
            <button type="submit" class="btn btn-success">Cập nhật</button>
        </form>
    </div>
</div>
<!-- Thêm script để tự động tắt thông báo sau 3 giây -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let alert = document.querySelector(".alert");
            if (alert) {
                alert.classList.add("fade");
                setTimeout(() => alert.remove(), 500); // Xóa hẳn phần tử sau khi fade out
            }
        }, 3000); // 3000ms = 3 giây
    });
</script>

@endsection
