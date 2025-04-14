@extends('adminlte::page')
<meta name="csrf-token" content="{{ csrf_token() }}">   
@section('title', 'Admin Dashboard')

@section('content_header')
    <h1>Trang quản trị</h1>
@stop


@section('content')
    @yield('admin-content')
@stop

<style>
    
    .main-sidebar {
    height: 100vh;
    overflow-y: auto;
}
.content-wrapper {
    min-height: 100vh;
    margin-left: 250px; /* Đảm bảo không đè lên sidebar */
}

    .main-sidebar .sidebar-menu > li:last-child {
        margin-top: auto;
    }
    .main-sidebar {
    height: 100vh; /* Đặt chiều cao bằng toàn màn hình */
    overflow-y: auto; /* Thêm thanh cuộn nếu nội dung quá dài */
    position: fixed; /* Giữ cố định */
    top: 0;
    left: 0;
    bottom: 0;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- layouts/master.blade.php -->
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('scripts')
</head>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Yield scripts từ view -->
@yield('scripts')

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#description')).catch(error => console.error(error));

    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'preview-img', 'm-2');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>

<style>
    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
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
.image-wrapper {
    width: 100px;
    height: 100px;
    position: relative;
    display: inline-block;
}

.image-wrapper .delete-image {
    position: absolute;
    top: 2px;
    right: 2px;
    padding: 0 6px;
    font-size: 14px;
    line-height: 1;
    z-index: 10;
}
</style>
<!-- Thêm script để tự động tắt thông báo sau 3 giây -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let alert = document.querySelector(".alert");
            if (alert) {
                alert.classList.add("fade");
                setTimeout(() => alert.remove(), 500); // Xóa hẳn phần tử sau khi fade out
            }
        }, 3000); // 3000ms = 3 giây
    });
</script>


