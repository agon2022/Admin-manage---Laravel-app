<?php

namespace App\Modules\Products\app\Http\Controllers;

use App\Modules\Category\app\Models\Category;
use App\Modules\ProductGallery\app\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            ->paginate(5)
            ->appends(request()->query());

        return view('products::index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products::create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'price' => str_replace('.', '', $request->price)
        ]);
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

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => strip_tags($request->description),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();

                // Kiểm tra ảnh trùng tên đã tồn tại trong gallery của sản phẩm này
                $existing = ProductGallery::where('image_path', 'like', "%{$originalName}")->where('product_id', $product->id)->first();
                if ($existing) {
                    continue; // Bỏ qua ảnh trùng
                }

                $filename = Str::random(6) . '_' . $originalName;
                $path = $image->storeAs('products', $filename, 'public');

                ProductGallery::create([
                    'product_id' => $product->id,
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
        $request->merge([
            'price' => str_replace('.', '', $request->price)
        ]);

        $request->validate(
            [
                'name' => 'required|string|max:255|unique:products,name,' . $product->id,
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

        $description = strip_tags($request->input('description'));

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'description' => $description,
        ]);

        if ($request->filled('deleted_images')) {
            $deletedIds = explode(',', $request->input('deleted_images'));
            foreach ($deletedIds as $id) {
                $image = ProductGallery::find($id);
                if ($image && $image->product_id == $product->id) {
                    Storage::delete('public/' . $image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $filename = Str::random(6) . '_' . $originalName;

                $existing = $product->gallery()->where('image_path', 'like', '%' . $originalName)->first();
                if ($existing) {
                    continue;
                }

                $path = $image->storeAs('products', $filename, 'public');
                $product->gallery()->create([
                    'image_path' => $path,
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
    public function deleteImage($id)
    {
        $image = ProductGallery::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh.'], 404);
        }

        // Xóa file ảnh vật lý
        Storage::disk('public')->delete($image->image_path);

        // Xóa bản ghi trong database
        $image->delete();

        return response()->json(['success' => true]);
    }
}
