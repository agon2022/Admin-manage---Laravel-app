<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('id', 'desc')->paginate(5)->appends(request()->query());

        return view('admin.roles.index', compact('roles'));
    }


    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        // Tạo role
        $role = Role::create(['name' => $request->name]);

        // Lấy permission theo ID (vì form submit permission id)
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');

        // Gán quyền theo tên
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Vai trò đã được tạo thành công.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        // Cập nhật tên
        $role->update(['name' => $request->name]);

        // Lấy tên permissions từ ID
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');

        // Gán quyền
        $role->syncPermissions($permissionNames);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }


    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
