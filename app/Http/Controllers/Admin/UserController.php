<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('roles') // Lấy danh sách users kèm roles
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc') // 🔥 User mới nhất lên đầu
            ->paginate(7);

        return view('admin.users.index', compact('users'));
    }



    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array', // Đảm bảo roles là mảng
        ], [
            'password.confirmed' => 'Mật khẩu xác nhận không khớp, vui lòng nhập lại!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);
        // Nếu xác nhận mật khẩu sai

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🛠 Gán Role (dùng tên thay vì ID)
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // 🛠 Gán Permission (dùng tên)
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'Thêm User thành công!');
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Lấy danh sách tất cả Role
        $permissions = Permission::all();
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // 🛠 Cập nhật Roles (phải là array)
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // 🛠 Cập nhật Permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'Cập nhật User thành công!');
    }



    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Xóa thành công!');
    }
}
