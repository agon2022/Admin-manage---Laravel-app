@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>{{ isset($product) ? 'Chỉnh Sửa Sản Phẩm' : 'Thêm Sản Phẩm' }}</h4>
        </div>

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
            <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif
                
                <div class="form-group">
                    <label for="name">Tên Sản Phẩm:</label>
                    <input type="text" name="name" class="form-control" required 
                           value="{{ $product->name ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="price">Giá:</label>
                    <input type="number" name="price" class="form-control" required 
                           value="{{ $product->price ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="category_id">Danh Mục:</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nếu có sản phẩm, hiển thị ảnh đã có -->
                @if(isset($product) && $product->gallery->count())
                <div class="form-group">
                    <label>Hình Ảnh Hiện Có:</label>
                    <div class="row">
                        @foreach ($product->gallery as $image)
                        <div class="col-4 position-relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 class="img-thumbnail" style="height: 100px; object-fit: cover;">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> Xóa
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="images">Chọn Hình Ảnh Mới:</label>
                    <input type="file" name="images[]" class="form-control" multiple onchange="previewImages(event)">
                </div>

                <!-- Hiển thị ảnh preview -->
                <div id="image-preview" class="mt-3 d-flex"></div>

                <div class="form-group">
                    <label for="description">Mô Tả:</label>
                    <textarea name="description" class="form-control" id="description" rows="5">
                        {{ $product->description ?? '' }}
                    </textarea>
                </div>

                <button type="submit" class="btn btn-success mt-3">Lưu</button>
            </form>
        </div>
    </div>
</div>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#description'))
        .catch(error => console.error(error));

    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Xóa các ảnh cũ

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.margin = '5px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
