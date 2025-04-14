@extends('admin.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add Booking</h4>
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
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
                        @csrf

                        <!-- Select User -->
                        <div class="form-group mb-3">
                            <label for="user_id">Select Customer</label>
                            <select class="form-control select2" id="user_id" name="user_id" required>
                                <option value="">-- Select Customer --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Items -->
                        <div id="product-list">
                            <div class="row mb-2 product-item" data-index="0">
                                <div class="col-md-7">
                                    <select name="products[0][id]" class="form-control select2 product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }} VNĐ</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="products[0][quantity]" class="form-control product-qty" placeholder="Quantity" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove-product">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add Product Button -->
                        <button type="button" class="btn btn-info mb-3" id="add-product">
                            <i class="fas fa-plus-circle"></i> Add Product
                        </button>

                        <!-- Total Price -->
                        <div class="form-group mb-3">
                            <label>Total Price</label>
                            <input type="text" class="form-control" id="total_price_display" value="0 VNĐ" readonly>
                            <input type="hidden" name="total_price" id="total_price">
                        </div>
                        <!-- Status -->
                        <div class="form-group mb-4">
                            <label>Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Booking
                            </button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const products = @json($products);

    const productData = {};
    products.forEach(product => {
        productData[product.id] = product.price;
    });

    function updateTotalPrice() {
        let total = 0;
        $('#product-list .product-item').each(function () {
            const productId = $(this).find('select').val();
            const quantity = $(this).find('input[type="number"]').val();
            if (productId && quantity) {
                const price = productData[productId] || 0;
                total += price * parseInt(quantity);
            }
        });

        $('#total_price').val(total);
        $('#total_price_display').val(total.toLocaleString() + ' VNĐ');
    }

    function getSelectedProductIds() {
        const selected = [];
        $('#product-list .product-item select').each(function () {
            const val = $(this).val();
            if (val) selected.push(val);
        });
        return selected;
    }

    function refreshProductOptions() {
        const selected = getSelectedProductIds();

        $('#product-list .product-item').each(function () {
            const currentSelect = $(this).find('select');
            const currentValue = currentSelect.val();

            currentSelect.find('option').each(function () {
                const optionValue = $(this).val();
                if (optionValue === "" || optionValue === currentValue) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', selected.includes(optionValue));
                }
            });
        });
    }

    function createProductItem(index) {
        const selected = getSelectedProductIds();
        const availableProducts = products.filter(p => !selected.includes(p.id.toString()));

        if (availableProducts.length === 0) {
            alert('Tất cả sản phẩm đã được chọn.');
            return '';
        }

        const options = availableProducts.map(product =>
            `<option value="${product.id}">${product.name} - ${Number(product.price).toLocaleString()} VNĐ</option>`
        ).join('');

        return `
            <div class="row mb-2 product-item" data-index="${index}">
                <div class="col-md-7">
                    <select name="products[${index}][id]" class="form-control select2 product-select" required>
                        <option value="">-- Select Product --</option>
                        ${options}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[${index}][quantity]" class="form-control product-qty" placeholder="Quantity" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-product">
                        <i class="fas fa-minus-circle"></i>
                    </button>
                </div>
            </div>
        `;
    }

    $(document).ready(function () {
        const today = new Date().toISOString().split('T')[0];
        $('#booking_date').val(today);

        $('#add-product').click(function () {
            const currentItems = $('#product-list .product-item');
            const index = currentItems.length;

            const newItem = createProductItem(index);
            if (!newItem) return;

            $('#product-list').append(newItem);
            $('#product-list .product-item').last().find('.select2').select2();
            refreshProductOptions();
            updateTotalPrice();
        });

        $(document).on('click', '.btn-remove-product', function () {
            $(this).closest('.product-item').remove();
            refreshProductOptions();
            updateTotalPrice();
        });

        $(document).on('change', '.product-select, .product-qty', function () {
            refreshProductOptions();
            updateTotalPrice();
        });

        $('#booking-form').on('submit', function (e) {
            let isValid = true;

            $('#product-list .product-item').each(function () {
                const productId = $(this).find('select').val();
                const quantity = $(this).find('input[type="number"]').val();

                if (!productId || !quantity || parseInt(quantity) <= 0) {
                    isValid = false;
                    alert('Vui lòng chọn sản phẩm và nhập số lượng hợp lệ cho tất cả các mục.');
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });

        $('.select2').select2();
    });
</script>
@endsection
