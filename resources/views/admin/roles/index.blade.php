@extends('admin.layouts.master')

@section('admin-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách Vai trò</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary float-right">Thêm Vai trò</a>
    </div>

    {{-- Hiển thị thông báo --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Hiển thị lỗi --}}
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
        {{-- Form tìm kiếm --}}
        <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên vai trò" value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex justify-content-end">
                    <button type="submit" class="btn btn-info">Tìm kiếm</button>
                </div>
            </div>
        </form>

        {{-- Bảng danh sách roles --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên vai trò</th>
                    <th>Chức năng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $roles->firstItem() + $loop->index }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.roles.edit', $role->id) }}">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
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

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $roles->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
