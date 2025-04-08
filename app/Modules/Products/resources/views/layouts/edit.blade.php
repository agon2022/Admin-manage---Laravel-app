@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Chỉnh Sửa Sản Phẩm</h4>
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
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
                <div class="form-group">
                    <label for="name">Tên Sản Phẩm:</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
            
                <div class="form-group">
                    <label for="price">Giá:</label>
                    <input type="number" name="price" class="form-control" value="{{number_format($product->price, 0, '', '') }}" required>
                </div>
            
                <div class="form-group">
                    <label for="category_id">Danh Mục:</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Thêm trường mô tả -->
                <div class="form-group">
                    <label for="description">Mô Tả:</label>
                    <textarea name="description" class="form-control" id="description" rows="4" required>{{ $product->description }}</textarea>
                </div>
            
                <!-- Hiển thị ảnh hiện tại -->
                <div class="form-group">
                    <label>Hình Ảnh Hiện Tại:</label>
                    <div class="row">
                        @foreach ($product->gallery as $image)
                            <div class="col-md-3">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail preview-img">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chọn ảnh mới -->
                <div class="form-group">
                    <label for="images">Chọn Ảnh Mới:</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="imageUpload">
                </div>

                <!-- Hiển thị ảnh preview -->
                <div class="form-group">
                    <label>Xem Trước Ảnh Mới:</label>
                    <div class="row" id="previewImages"></div>
                </div>
            
                <button type="submit" class="btn btn-primary mt-3">Cập Nhật</button>
            </form>
        </div>
    </div>
</div>

<!-- Script Preview Ảnh -->
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
@endsection
