<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    // Hiển thị thông tin hồ sơ người dùng
    public function index()
    {
        $user = User::find(Auth::id());

        return view('admin.profile.index', compact('user'));
    }

    // Hiển thị form chỉnh sửa
    public function edit()
    {
        $user = User::find(Auth::id());
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request, $profile)
    {
        $user = User::findOrFail($profile);

        // Cập nhật thông tin người dùng
        $user->update($request->only('name')); // Cập nhật name

        // Cập nhật avatar nếu có
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatar;
        }

        // Lưu thông tin người dùng
        $user->save();

        // Chuyển hướng lại trang hồ sơ
        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully');
    }




    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }

        // Cập nhật mật khẩu mới
        if ($user instanceof User) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            return back()->withErrors(['user' => 'User not found']);
        }

        return redirect()->route('admin.profile.index')->with('success', 'Password updated successfully');
    }
}
