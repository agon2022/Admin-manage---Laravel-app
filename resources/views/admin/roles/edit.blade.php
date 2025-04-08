<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Tên Role</label>
        <input type="text" name="name" class="form-control" value="{{ $role->name }}">
    </div>
    <div class="form-group">
        <label>Chọn Permissions</label><br>
        @foreach ($permissions as $permission)
            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
            {{ $permission->name }} <br>
        @endforeach
    </div>
    <button type="submit" class="btn btn-success">Cập nhật Role</button>
</form>
