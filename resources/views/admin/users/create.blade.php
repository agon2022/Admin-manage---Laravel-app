@extends('admin.layouts.master')
@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('admin-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm người dùng</h3>
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
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Tên</label>
                <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label>Email (Tên tài khoản)</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control">
                @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="role">Chọn Role</label>
                <select name="role" id="role"
                    class="form-control select2-role"
                    style="width: 100%;">
                    <option value="">-- Chọn role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            
                    
            <button type="submit" class="btn btn-success">Thêm User</button>
        </form>
    </div>
</div>

@endsection
@push('scripts')
    <!-- jQuery (nếu chưa có) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            $('.select2-role').select2({
                placeholder: "Tìm kiếm và chọn role...",
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: Infinity, // Đảm bảo thanh tìm kiếm luôn hiển thị
                language: {
                    noResults: function() {
                        return "Không tìm thấy kết quả";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                }
            });
        });
    </script>
@endpush

