<?php

namespace App\Modules\Products\app\Http\Controllers;

use App\Modules\Category\app\Models\Category;
use App\Modules\ProductGallery\app\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Modules\Products\app\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->with('category')
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('products::index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products::create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:products,name',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp'
            ],
            [
                'name.unique' => 'Tên sản phẩm đã tồn tại. Vui lòng chọn tên khác.',
                'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
                'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            ]
        );

        // 🟢 Lưu sản phẩm trước khi lưu ảnh
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => strip_tags($request->description), // Loại bỏ HTML
        ]);

        // 🟢 Kiểm tra nếu có hình ảnh thì lưu vào bảng `products_gallery`
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                ProductGallery::create([
                    'product_id' => $product->id, // 🟢 Chắc chắn có ID sản phẩm
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }


    public function edit($id)
    {
        $categories = Category::all();
        $product = Product::with('gallery')->findOrFail($id); // Load quan hệ đúng
        return view('products::edit', compact('product', 'categories'));
    }



    public function update(Request $request, Product $product)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:products,name',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp'
            ],
            [
                'name.unique' => 'Tên sản phẩm đã tồn tại. Vui lòng chọn tên khác.',
                'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
                'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            ]
        );
        // Loại bỏ các thẻ HTML trong mô tả
        $description = strip_tags($request->input('description'));

        // Cập nhật sản phẩm với mô tả không có thẻ HTML
        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'description' => $description, // Cập nhật mô tả đã được loại bỏ thẻ HTML
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            // Xóa hình ảnh cũ nếu có
            if ($product->gallery && $product->gallery->count() > 0) {
                foreach ($product->gallery as $image) {
                    Storage::delete('public/' . $image->image_path);
                    $image->delete();
                }
            }

            // Lưu hình ảnh mới
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
    public function show($id)
    {
        $product = Product::with('category', 'gallery')->findOrFail($id); // Lấy sản phẩm theo ID

        return view('products::show', compact('product')); // Trả về view với thông tin sản phẩm
    }
}
