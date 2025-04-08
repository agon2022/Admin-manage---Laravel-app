@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

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

            <!-- Form chỉnh sửa hồ sơ -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">

                    <!-- Avatar Preview -->
                    <div class="mb-3">
                        <!-- Hiển thị ảnh đại diện hiện tại -->
                        <img id="avatarPreview" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/default-avatar.png') }}" 
                             class="rounded-circle border border-3 border-primary shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <form action="{{ route('admin.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Sử dụng PUT cho việc cập nhật -->
                        
                        <!-- Tên người dùng -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Avatar (Ảnh đại diện) -->
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control" onchange="previewAvatar(event)">
                            @if($user->avatar)
                                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="mt-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script preview avatar -->
<script>
    function previewAvatar(event) {
        var reader = new FileReader();
        reader.onload = function() {
            // Cập nhật ảnh preview khi người dùng chọn ảnh mới
            document.getElementById('avatarPreview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
