<?php

namespace App\Modules\ProductGallery\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\ProductGallery\app\Models\ProductGallery as ModelsProductGallery;
use Illuminate\Support\Facades\Storage;
use App\Modules\ProductGallery\app\Models\ProductGallery;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('productgallery::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productgallery::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('productgallery::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('productgallery::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = ProductGallery::findOrFail($id);

        // Xoá file vật lý
        if (Storage::exists($image->image_path)) {
            Storage::delete($image->image_path);
        }

        // Xoá bản ghi khỏi database
        $image->delete();

        return back()->with('success', 'Xóa ảnh thành công!');
    }
}
