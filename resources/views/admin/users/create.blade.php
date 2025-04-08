@extends('admin.layouts.master')

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
    <label>Chọn Role</label>
    <select name="roles[]" class="form-control">
        @foreach ($roles as $role)
            <option value="{{ $role->name }}">{{ $role->name }}</option>
        @endforeach
    </select>
</div>

            <!-- Chọn Permission (Checkbox) -->
            <div class="form-group">
                <label>Chọn Quyền (Permissions)</label>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-success">Thêm User</button>
        </form>
    </div>
</div>
@endsection
