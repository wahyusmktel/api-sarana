<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AssetCategoryController extends Controller
{
    public function index()
    {
        try {
            return response()->json(AssetCategory::all());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengambil data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_categories,name',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $data = AssetCategory::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json(['message' => 'Kategori berhasil dibuat', 'data' => $data], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal membuat kategori', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $category = AssetCategory::findOrFail($id);
            return response()->json($category);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Kategori tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $category = AssetCategory::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_categories,name,' . $id,
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $category->update($request->only(['name', 'description']));

            return response()->json(['message' => 'Kategori berhasil diperbarui', 'data' => $category]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update kategori', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = AssetCategory::findOrFail($id);
            $category->delete();

            return response()->json(['message' => 'Kategori berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus kategori', 'error' => $e->getMessage()], 500);
        }
    }
}
