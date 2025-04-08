<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Tên Role</label>
        <input type="text" name="name" class="form-control">
    </div>
    <div class="form-group">
        <label>Chọn Permissions</label><br>
        @foreach ($permissions as $permission)
            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
            {{ $permission->name }} <br>
        @endforeach
    </div>
    <button type="submit" class="btn btn-success">Tạo Role</button>
</form>
