<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AssetConditionController extends Controller
{
    public function index()
    {
        try {
            return response()->json(AssetCondition::all());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengambil data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_conditions,name',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $data = AssetCondition::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json(['message' => 'Kondisi berhasil dibuat', 'data' => $data], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal membuat kondisi', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            return response()->json(AssetCondition::findOrFail($id));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Kondisi tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $condition = AssetCondition::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:asset_conditions,name,' . $id,
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $condition->update($request->only(['name', 'description']));

            return response()->json(['message' => 'Kondisi berhasil diperbarui', 'data' => $condition]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update kondisi', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $condition = AssetCondition::findOrFail($id);
            $condition->delete();

            return response()->json(['message' => 'Kondisi berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus kondisi', 'error' => $e->getMessage()], 500);
        }
    }
}
