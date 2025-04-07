<?php

// app/Http/Controllers/Api/AssetUsageController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\AssetUsage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssetUsageController extends Controller
{
    public function index()
    {
        $data = AssetUsage::with(['asset', 'user'])->latest()->get();
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        Log::info('Memulai proses penyimpanan penggunaan aset');

        $request->validate([
            'asset_id' => 'required|uuid|exists:assets,id',
            'user_id' => 'required|uuid|exists:users,id',
            'usage_type' => 'nullable|string',
            'description' => 'nullable|string',
            'used_at' => 'required|date',
            'finished_at' => 'nullable|date',
            'condition_after' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        Log::info('Validasi berhasil', ['request' => $request->all()]);

        $data = $request->except('photo'); // ambil semua kecuali photo

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('asset-usage-photos', 'public');
            $data['photo'] = $path; // simpan path saja, diakses nanti pakai asset('storage/'.$path)
            Log::info('Foto berhasil diunggah', ['path' => $path]);
        }

        $data['id'] = Str::uuid();
        $usage = AssetUsage::create($data);

        Log::info('Penggunaan aset berhasil disimpan', ['usage' => $usage]);

        return response()->json([
            'message' => 'Penggunaan aset ditambahkan',
            'data' => $usage
        ], 201);
    }

    public function update(Request $request, $id)
    {
        Log::info('Memulai update penggunaan aset', ['id' => $id]);

        $request->validate([
            'asset_id' => 'required|uuid|exists:assets,id',
            'user_id' => 'required|uuid|exists:users,id',
            'usage_type' => 'nullable|string',
            'description' => 'nullable|string',
            'used_at' => 'required|date',
            'finished_at' => 'nullable|date',
            'condition_after' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $usage = AssetUsage::findOrFail($id);

        $data = $request->all();

        // Jika ada file baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($usage->photo && Storage::disk('public')->exists(str_replace('storage/', '', $usage->photo))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $usage->photo));
            }

            $path = $request->file('photo')->store('asset-usage-photos', 'public');
            $data['photo'] = 'storage/' . $path;
        }

        $usage->update($data);

        return response()->json([
            'message' => 'Data penggunaan aset berhasil diperbarui',
            'data' => $usage
        ]);
    }

    public function show($id)
    {
        $usage = AssetUsage::with(['asset', 'user'])->findOrFail($id);
        return response()->json(['data' => $usage]);
    }

    public function destroy($id)
    {
        $usage = AssetUsage::findOrFail($id);
        $usage->delete();

        return response()->json(['message' => 'Penggunaan aset dihapus']);
    }
}
