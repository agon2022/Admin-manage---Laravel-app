@extends('admin.layouts.master')

@section('admin-content')
<div class="wrapper"> <!-- Đảm bảo toàn bộ trang có chiều cao đầy đủ -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Danh sách sản phẩm</h3>
            <a href="{{ route('products.create') }}" class="btn btn-primary float-right">Thêm mới</a>
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
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('products.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên sản phẩm" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-info">Tìm kiếm</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Hình Ảnh</th>
                        <th>Danh Mục</th>
                        <th>Mô Tả</th> 
                        <th>Giá</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            @if($product->gallery->count() > 0)
                                <div class="image-gallery">
                                    <!-- Ảnh chính -->
                                    <img src="{{ asset('storage/' . $product->gallery->first()->image_path) }}" 
                                         class="main-image" width="100" height="120"
                                         data-default="{{ asset('storage/' . $product->gallery->first()->image_path) }}">
                        
                                    <!-- Ảnh nhỏ -->
                                    @if($product->gallery->count() > 1)
                                        <div class="thumbnail-container">
                                            @foreach ($product->gallery->slice(1) as $image)
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     class="thumbnail" width="30" height="30" 
                                                     onclick="changeMainImage(this)">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span>Chưa có ảnh</span>
                            @endif
                        </td>                                           
                        <td>{{ $product->category->name }}</td>
                        <td>{!! Str::limit($product->description, 50) !!}</td> <!-- Hiển thị mô tả với giới hạn 50 ký tự -->
                        <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('products.show', $product->id) }}">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            

            <!-- Hiển thị phân trang -->
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>        
        </div>
    </div>
</div>

<!-- CSS khắc phục sidebar -->
<style>
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

.image-gallery {
    display: flex;
    align-items: flex-end; /* Căn hình ảnh nhỏ xuống chân của hình ảnh lớn */
    gap: 10px; /* Khoảng cách giữa ảnh lớn và ảnh nhỏ */
}

.main-image {
    transition: opacity 0.3s ease-in-out;
    border: 2px solid #ddd;
    cursor: pointer;
}

.thumbnail-container {
    display: flex;
    flex-direction: row; /* Ảnh nhỏ nằm ngang */
    gap: 5px;
}

.thumbnail {
    border: 1px solid #ccc;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
}

.thumbnail:hover {
    transform: scale(1.2);
}
</style>

<script>
function changeMainImage(element) {
    let mainImage = element.closest('.image-gallery').querySelector('.main-image');

    // Hoán đổi ảnh giữa ảnh chính và ảnh nhỏ
    let tempSrc = mainImage.src;
    mainImage.src = element.src;
    element.src = tempSrc;
}
</script>

    
        
@endsection
