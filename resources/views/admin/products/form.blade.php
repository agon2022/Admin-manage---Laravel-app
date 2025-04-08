@extends('admin.layouts.master')
@section('content')
<form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($product))
        @method('PUT') <!-- Chỉ dùng phương thức PUT khi là sửa -->
    @endif

    <div class="form-group">
        <label for="name">Tên Sản Phẩm:</label>
        <input type="text" name="name" class="form-control" value="{{ isset($product) ? $product->name : old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" name="price" class="form-control" value="{{ isset($product) ? $product->price : old('price') }}" required>
    </div>

    <div class="form-group">
        <label for="category_id">Danh Mục:</label>
        <select name="category_id" class="form-control" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="description">Mô Tả:</label>
        <textarea name="description" class="form-control" id="description" rows="5">{{ isset($product) ? $product->description : old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="images">Hình Ảnh:</label>
        <input type="file" name="images[]" class="form-control" multiple onchange="previewImages(event)">
    </div>

    <div id="image-preview" class="mt-3"></div> <!-- Container for image previews -->

    <button type="submit" class="btn btn-{{ isset($product) ? 'primary' : 'success' }} mt-3">
        {{ isset($product) ? 'Lưu' : 'Tạo mới' }}
    </button>
</form>
@endsection
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#description'))
        .catch(error => {
            console.error(error);
        });

    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Clear previous previews

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '150px';
                img.style.margin = '5px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        let previewContainer = document.getElementById('previewImages');
        previewContainer.innerHTML = ''; // Xóa ảnh preview cũ
        let files = event.target.files;

        Array.from(files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'preview-img', 'm-2');
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>

<!-- CSS Điều Chỉnh Kích Thước -->
<style>
    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
</style>

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>

<script>
    // Khởi tạo CKEditor cho trường mô tả
    ClassicEditor
        .create(document.querySelector('#description'))
        .catch(error => {
            console.error(error);
        });
</script>