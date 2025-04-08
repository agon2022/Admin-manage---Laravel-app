@extends('admin.layouts.master')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">{{ $product->name }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-6">
                    <div class="text-center mb-3">
                        <!-- Hình ảnh chính -->
                        <img id="main-image" src="{{ asset('storage/' . $product->gallery->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow" 
                             style="max-height: 300px; object-fit: cover;">
                    </div>
                    
                    <div class="row">
                        @foreach ($product->gallery as $image)
                            <div class="col-4 mb-2">
                                <!-- Hình ảnh nhỏ -->
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-thumbnail shadow-sm" 
                                     style="height: 100px; width: 100%; object-fit: cover;"
                                     onclick="changeMainImage(this)">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Thông tin sản phẩm -->
                <div class="col-md-6">
                    <h4 class="text-secondary">Thông tin sản phẩm</h4>
                    <hr>
                    <p><strong>Giá:</strong> <span class="text-danger font-weight-bold">{{ number_format($product->price, 0) }} VNĐ</span></p>
                    <p><strong>Danh mục:</strong> <span class="badge bg-info">{{ $product->category->name }}</span></p>
                    <p><strong>Mô tả:</strong> {!! nl2br(e(Str::limit($product->description, 200))) !!}</p>
                    
                </div>
            </div>
            
            <!-- Nút quay lại -->
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
    .img-thumbnail {
        transition: transform 0.2s;
    }
    .img-thumbnail:hover {
        transform: scale(1.1);
    }
</style>

<!-- JavaScript -->
<script>
    function changeMainImage(element) {
        // Lấy ảnh chính và thay đổi nguồn ảnh
        const mainImage = document.getElementById('main-image');
        mainImage.src = element.src; // Cập nhật ảnh chính với ảnh nhỏ được click
    }
</script>
@endsection
