<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AssetLocationController extends Controller
{
    public function index()
    {
        try {
            return response()->json(AssetLocation::all());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengambil lokasi', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'room_code' => 'nullable|string',
                'building_name' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $data = AssetLocation::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'room_code' => $request->room_code,
                'building_name' => $request->building_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'description' => $request->description,
            ]);

            return response()->json(['message' => 'Lokasi berhasil ditambahkan', 'data' => $data], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menyimpan lokasi', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            return response()->json(AssetLocation::findOrFail($id));
        } catch (Throwable $e) {
            return response()->json(['message' => 'Lokasi tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $location = AssetLocation::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'room_code' => 'nullable|string',
                'building_name' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $location->update($request->only([
                'name',
                'room_code',
                'building_name',
                'latitude',
                'longitude',
                'description'
            ]));

            return response()->json(['message' => 'Lokasi berhasil diperbarui', 'data' => $location]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update lokasi', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $location = AssetLocation::findOrFail($id);
            $location->delete();

            return response()->json(['message' => 'Lokasi berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus lokasi', 'error' => $e->getMessage()], 500);
        }
    }
}
