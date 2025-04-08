@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar Preview -->
                    <div class="mb-3">
                        <img id="avatarPreview" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/default-avatar.png') }}" 
                             class="rounded-circle border border-3 border-primary shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>

                    <!-- Form chỉnh sửa hồ sơ -->
                    <form action="{{ route('admin.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Sử dụng PUT cho việc cập nhật -->

                        <!-- Tên người dùng -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email người dùng (không cho thay đổi) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
                        </div>

                        <!-- Avatar (ảnh đại diện) -->
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control" onchange="previewAvatar(event)">
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
            document.getElementById('avatarPreview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
