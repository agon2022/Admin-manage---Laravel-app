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

            <!-- Thông tin User -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>User Profile</h4>
                </div>
                <div class="card-body text-center">
                    <!-- Hiển thị ảnh đại diện -->
                    @if(isset($user) && $user->avatar)
    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle border border-3 border-primary shadow-sm" 
         style="width: 120px; height: 120px; object-fit: cover;">
@else
    <img src="{{ asset('storage/default-avatar.png') }}" class="rounded-circle border border-3 border-secondary shadow-sm" 
         style="width: 120px; height: 120px; object-fit: cover;">
@endif

                    <h4 class="mt-3">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <a href="{{ route('admin.profile.edit', ['profile' => $user->id]) }}">Chỉnh sửa hồ sơ</a>


                </div>
            </div>

            <!-- Đổi mật khẩu -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-dark text-center">
                    <h4>Change Password</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.changePassword') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label"><i class="fas fa-lock"></i> Current Password</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label"><i class="fas fa-key"></i> New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label"><i class="fas fa-key"></i> Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<style>
    .main-sidebar {
    height: 100vh;
    overflow-y: auto;
    position: fixed;
}

.wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    min-height: 100vh;
}

</style>