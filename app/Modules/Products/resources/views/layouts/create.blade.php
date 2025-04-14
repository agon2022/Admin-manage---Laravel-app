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
                    <input type="text" id="price" name="price" class="form-control" required 
                           value="{{ isset($product) ? number_format($product->price, 0, '', '.') : '' }}">
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
                    <label for="images">Chọn Hình Ảnh:</label>
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
                <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
            </form>
        </div>
    </div>
</div>

<script>
    ClassicEditor.create(document.querySelector('#description'))
        .catch(error => console.error(error));
</script>
<script>
    const priceInput = document.getElementById('price');

    function formatPriceVND(value) {
        value = value.replace(/\D/g, ''); // Loại bỏ ký tự không phải số
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    priceInput.addEventListener('input', function (e) {
        const cursorPosition = priceInput.selectionStart;
        const rawValue = priceInput.value;
        const formatted = formatPriceVND(rawValue);
        priceInput.value = formatted;

        // Giữ lại vị trí con trỏ
        const newCursorPosition = cursorPosition + (formatted.length - rawValue.length);
        priceInput.setSelectionRange(newCursorPosition, newCursorPosition);
    });

    // Trước khi submit, loại bỏ dấu chấm để gửi về server dạng số
    document.querySelector('form').addEventListener('submit', function () {
        priceInput.value = priceInput.value.replace(/\./g, '');
    });
</script>
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

<script>
    let selectedFiles = [];

    function previewImages(event) {
        const files = Array.from(event.target.files);
        selectedFiles = files;

        renderImagePreview();
    }

    function renderImagePreview() {
    const previewContainer = document.getElementById('image-preview');
    previewContainer.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgWrapper = document.createElement('div');
            imgWrapper.className = 'image-wrapper me-2'; // sử dụng class giống edit
            imgWrapper.style.display = 'inline-block';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';

            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.type = 'button';
            closeBtn.className = 'btn btn-sm btn-danger delete-image'; // giống edit
            closeBtn.onclick = () => {
                selectedFiles.splice(index, 1);
                renderImagePreview();
            };

            imgWrapper.appendChild(img);
            imgWrapper.appendChild(closeBtn);
            previewContainer.appendChild(imgWrapper);
        };
        reader.readAsDataURL(file);
    });

    updateFileInput();
}


    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        document.querySelector('input[name="images[]"]').files = dataTransfer.files;
    }
</script>

<style>


</style>
@endsection
