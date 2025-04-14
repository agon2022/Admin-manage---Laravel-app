<?php

namespace App\Modules\Category\app\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Category\app\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate(5)
            ->appends(request()->query());

        return view('Category::index', compact('categories')); // ✅ Sửa đường dẫn View
    }

    public function create()
    {
        return view('Category::create'); // ✅ Sửa đường dẫn View
    }

    public function store(Request $request)
    {
        // Loại bỏ thẻ HTML khỏi các trường 'name', 'description' và 'content'
        $request->merge([
            'name' => strip_tags($request->input('name')),
            'description' => strip_tags($request->input('description')),
            'content' => strip_tags($request->input('content')),
        ]);

        $request->validate([
            'name' => 'required|unique:categories,name', // Kiểm tra tính duy nhất của tên
            'description' => 'nullable|string',
            'content' => 'nullable|string',
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại. Vui lòng chọn tên khác.', // Thông báo lỗi nếu tên đã tồn tại
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
        ]);

        return redirect()->route('category.index')->with('success', 'Thêm danh mục thành công!'); // ✅ Sửa route
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('Category::edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Loại bỏ thẻ HTML khỏi các trường 'name', 'description' và 'content'
        $request->merge([
            'name' => strip_tags($request->input('name')),
            'description' => strip_tags($request->input('description')),
            'content' => strip_tags($request->input('content')),
        ]);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id, // Không kiểm tra tên hiện tại của chính danh mục
            'description' => 'nullable|string',
            'content' => 'nullable|string',
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại. Vui lòng chọn tên khác.', // Thông báo lỗi nếu tên đã tồn tại
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
        ]);

        return redirect()->route('category.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Xóa danh mục thành công!');
    }
}
