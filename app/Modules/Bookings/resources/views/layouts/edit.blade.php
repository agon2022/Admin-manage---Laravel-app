@extends('admin.layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h3>Edit Booking</h3>
        </div>

        <!-- Thông báo -->
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
            <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Chọn User -->
                <div class="mb-3">
                    <label>User:</label>
                    <select name="user_id" class="form-control select2" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sản phẩm động -->
                <div class="mb-3">
                    <label>Products:</label>
                    <div id="product-list">
                        @foreach ($booking->products as $index => $product)
                            <div class="row mb-2 product-item">
                                <div class="col-md-7">
                                    <select name="products[{{ $index }}][id]" class="form-control select2" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} - {{ number_format($p->price, 0, ',', '.') }} VNĐ
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="products[{{ $index }}][quantity]" value="{{ $product->pivot->quantity }}" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove-product">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-info mt-2" id="add-product">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </button>
                </div>

                <!-- Tổng tiền -->
                <div class="form-group mb-3">
                    <label><strong>Total:</strong></label>
                    <input type="text" class="form-control" id="total-price" readonly value="0 VNĐ">
                </div>
                <!-- Trạng thái -->
                <div class="mb-4">
                    <label>Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="canceled" {{ $booking->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const productsData = @json($products);
</script>

<script>
    $(document).ready(function () {
        let index = $('#product-list .product-item').length;

        function getSelectedProductIds() {
            let ids = [];
            $('#product-list .product-item select').each(function () {
                const val = $(this).val();
                if (val) ids.push(parseInt(val));
            });
            return ids;
        }

        function generateProductOptions(selectedId = null) {
            const selectedIds = getSelectedProductIds();
            return productsData.map(p => {
                const isSelected = selectedId == p.id ? 'selected' : '';
                const isDisabled = selectedId != p.id && selectedIds.includes(p.id) ? 'disabled' : '';
                return `<option value="${p.id}" ${isSelected} ${isDisabled}>${p.name} - ${Number(p.price).toLocaleString()} VNĐ</option>`;
            }).join('');
        }

        function updateSelectOptions() {
            $('#product-list .product-item select').each(function () {
                const selectedId = $(this).val();
                $(this).html('<option value="">-- Select Product --</option>' + generateProductOptions(selectedId));
            });
        }

        $('#add-product').click(function () {
            index = $('#product-list .product-item').length;

            let html = `
                <div class="row mb-2 product-item">
                    <div class="col-md-7">
                        <select name="products[${index}][id]" class="form-control select2" required>
                            <option value="">-- Select Product --</option>
                            ${generateProductOptions()}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="products[${index}][quantity]" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-product">
                            <i class="fas fa-minus-circle"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#product-list').append(html);
            $('.select2').select2();
            updateSelectOptions();
        });

        $(document).on('click', '.btn-remove-product', function () {
            $(this).closest('.product-item').remove();
            updateSelectOptions();
            updateTotal();
        });

        $(document).on('change', 'select[name^="products"]', function () {
            updateSelectOptions();
            updateTotal();
        });

        $(document).on('input', 'input[name^="products"]', updateTotal);

        function updateTotal() {
            let total = 0;
            $('#product-list .product-item').each(function () {
                const productId = $(this).find('select').val();
                const quantity = parseInt($(this).find('input').val()) || 0;
                const product = productsData.find(p => p.id == productId);
                if (product) {
                    total += product.price * quantity;
                }
            });

            $('#total-price').val(total.toLocaleString() + ' VNĐ');
        }

        updateSelectOptions();
        updateTotal();
    });
</script>
@endsection

