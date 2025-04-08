<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AssetMaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = AssetMaintenance::with('asset')->latest()->get();
        return response()->json($maintenances);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'maintenance_type' => 'nullable|string',
            'performed_by' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'description' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $maintenance = AssetMaintenance::create([
            'id' => Str::uuid(),
            ...$validator->validated(),
        ]);

        return response()->json([
            'message' => 'Data pemeliharaan ditambahkan',
            'data' => $maintenance
        ]);
    }

    public function show($id)
    {
        $data = AssetMaintenance::with('asset')->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'maintenance_type' => 'nullable|string',
            'performed_by' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'description' => 'nullable|string',
            'performed_at' => 'required|date',
        ]);

        $maintenance->update($request->all());

        return response()->json([
            'message' => 'Data pemeliharaan diperbarui',
            'data' => $maintenance
        ]);
    }

    public function destroy($id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json(['message' => 'Data pemeliharaan dihapus']);
    }
}
