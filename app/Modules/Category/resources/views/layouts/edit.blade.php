@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Chỉnh Sửa Danh Mục</h4>
        </div>
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
        <div class="card-body">
            <form action="{{ route('category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên Danh Mục:</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea name="description" class="form-control" id="description">{{ old('description', $category->description) }}</textarea>
                </div>    
                <div class="form-group">
                    <label for="content">Nội dung danh mục</label>
                    <textarea class="form-control" id="content" name="content" rows="4">{{ old('content', $category->content ?? '') }}</textarea>
                </div>                            
                <button type="submit" class="btn btn-success mt-3">Cập Nhật</button>
                <a href="{{ route('category.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#content'), {
            height: '400px'
        })
        .then(editor => {
            editor.ui.view.editable.element.style.height = '400px';
        })
        .catch(error => {
            console.error(error);
        });
</script>
<style>
    /* Đảm bảo vùng editable của CKEditor luôn giữ chiều cao */
    .ck-editor__editable_inline {
        min-height: 400px !important;
        max-height: 400px !important;
        height: 400px !important;
        overflow-y: auto !important;
    }
    <!-- CSS khắc phục sidebar -->
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
@endsection
