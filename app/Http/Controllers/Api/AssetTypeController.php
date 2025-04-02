<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AssetTypeController extends Controller
{
    public function index()
    {
        try {
            return response()->json(AssetType::with('category')->get());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengambil data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_types,name',
                'category_id' => 'required|exists:asset_categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $data = AssetType::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'category_id' => $request->category_id,
            ]);

            return response()->json(['message' => 'Jenis aset berhasil ditambahkan', 'data' => $data], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            return response()->json(AssetType::with('category')->findOrFail($id));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Jenis aset tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $type = AssetType::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_types,name,' . $id,
                'category_id' => 'required|exists:asset_categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $type->update($request->only(['name', 'category_id']));

            return response()->json(['message' => 'Jenis aset berhasil diperbarui', 'data' => $type]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update jenis aset', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $type = AssetType::findOrFail($id);
            $type->delete();

            return response()->json(['message' => 'Jenis aset berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus jenis aset', 'error' => $e->getMessage()], 500);
        }
    }
}
