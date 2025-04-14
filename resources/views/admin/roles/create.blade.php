@extends('admin.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thêm Vai trò</h4>
                </div>

                {{-- Thông báo --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2 mx-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mt-2 mx-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        {{-- Tên vai trò --}}
                        <div class="form-group mb-3">
                            <label for="name">Tên vai trò</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên vai trò" required>
                        </div>

                        {{-- Chọn quyền --}}
                        <div class="form-group mb-3">
                            <label>Chọn quyền</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Nút hành động --}}
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Lưu vai trò
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
