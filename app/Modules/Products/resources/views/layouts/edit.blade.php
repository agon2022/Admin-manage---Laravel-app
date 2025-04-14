@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h4>Chỉnh Sửa Sản Phẩm</h4>
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
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="deleted_images" id="deletedImages">

                <div class="form-group">
                    <label for="name">Tên Sản Phẩm:</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <div class="form-group">
                    <label for="price">Giá:</label>
                    <input type="text" name="price" id="price" class="form-control" 
                           value="{{ number_format($product->price, 0, ',', '.') }}" required>
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

                <!-- Ảnh hiện tại -->
<div class="form-group">
    <label>Hình Ảnh Hiện Tại:</label>
    <div class="d-flex flex-wrap gap-2" id="existingImages">
        @foreach ($product->gallery as $image)
        <div class="image-wrapper position-relative" data-id="{{ $image->id }}">
            <img src="{{ asset('storage/' . $image->image_path) }}"
                 class="img-thumbnail"
                 style="height: 100px; width: 100px; object-fit: cover;">
            <button type="button"
                    class="btn btn-sm btn-danger delete-image"
                    data-id="{{ $image->id }}">
                &times;
            </button>
        </div>
        @endforeach
    </div>
</div>


                <!-- Chọn ảnh mới -->
                <div class="form-group">
                    <label for="images">Chọn Ảnh Mới:</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="imageUpload">
                </div>

                <!-- Xem trước ảnh mới -->
                <div class="form-group">
                    <label>Xem Trước Ảnh Mới:</label>
                    <div class="d-flex flex-wrap gap-2" id="previewImages"></div>
                </div>

                <div class="form-group">
                    <label for="description">Mô Tả:</label>
                    <textarea name="description" class="form-control" id="description" rows="4">{{ $product->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Cập Nhật</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
            </form>
        </div>
    </div>
</div>

<!-- Script CKEditor -->
<script>
    ClassicEditor.create(document.querySelector('#description'))
        .catch(error => console.error(error));
</script>

<!-- Script Preview ảnh -->
<script>
    document.getElementById('imageUpload').addEventListener('change', function (event) {
        let previewContainer = document.getElementById('previewImages');
        previewContainer.innerHTML = '';

        let files = Array.from(event.target.files);
        let dataTransfer = new DataTransfer();

        // Danh sách file name của ảnh đã có
        let existingImageNames = Array.from(document.querySelectorAll('#existingImages img')).map(img => {
            let pathParts = img.src.split('/');
            return pathParts[pathParts.length - 1]; // lấy tên file
        });

        files.forEach(file => {
            if (existingImageNames.includes(file.name)) {
                alert(`Ảnh "${file.name}" đã tồn tại và sẽ không được thêm lại.`);
                return;
            }

            dataTransfer.items.add(file); // chỉ thêm file hợp lệ vào input

            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'preview-img');
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });

        // Gán lại file hợp lệ cho input
        event.target.files = dataTransfer.files;
    });
</script>


<!-- Script format giá -->
<script>
    const priceInput = document.getElementById('price');
    function formatPriceVND(value) {
        value = value.replace(/\D/g, '');
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    priceInput.addEventListener('input', function () {
        const cursorPosition = priceInput.selectionStart;
        const rawValue = priceInput.value;
        const formatted = formatPriceVND(rawValue);
        priceInput.value = formatted;
        const newCursorPosition = cursorPosition + (formatted.length - rawValue.length);
        priceInput.setSelectionRange(newCursorPosition, newCursorPosition);
    });

    document.querySelector('form').addEventListener('submit', function () {
        priceInput.value = priceInput.value.replace(/\./g, '');
    });
</script>

<!-- Script xóa ảnh tạm thời -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deletedImages = [];

        document.querySelectorAll('.delete-image').forEach(button => {
            button.addEventListener('click', function () {
                const imageId = this.getAttribute('data-id');
                const container = this.closest('.image-wrapper');

                deletedImages.push(imageId);
                document.getElementById('deletedImages').value = deletedImages.join(',');
                container.remove();
            });
        });
    });
</script>

@endsection
