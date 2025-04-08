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
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
