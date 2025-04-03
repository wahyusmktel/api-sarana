<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class AssetController extends Controller
{
    use LogsActivity;
    public function index()
    {
        try {
            $assets = Asset::with(['category', 'type', 'condition', 'location', 'responsible'])->get();
            return response()->json($assets);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal mengambil data aset', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info('STEP 1: Request Masuk', $request->all());
        try {
            Log::info('STEP 2: Mulai Validasi');
            $validator = Validator::make($request->all(), [
                'asset_code' => 'required|unique:assets',
                'name' => 'required|string',
                'serial_number' => 'nullable|string',
                'category_id' => 'required|exists:asset_categories,id',
                'type_id' => 'nullable|exists:asset_types,id',
                'condition_id' => 'nullable|exists:asset_conditions,id',
                'location_id' => 'nullable|exists:asset_locations,id',
                'acquisition_date' => 'nullable|date',
                'acquisition_cost' => 'nullable|numeric',
                'funding_source' => 'nullable|string',
                'responsible_user_id' => 'nullable|exists:users,id',
            ]);

            if ($validator->fails()) {
                Log::warning('STEP 2.1: Validasi gagal', $validator->errors()->toArray());
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            Log::info('STEP 3: Validasi Lolos, Create Asset');

            $asset = Asset::create([
                'id' => Str::uuid(),
                'asset_code' => $request->asset_code,
                'name' => $request->name,
                'serial_number' => $request->serial_number,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'condition_id' => $request->condition_id,
                'location_id' => $request->location_id,
                'acquisition_date' => $request->acquisition_date ? Carbon::parse($request->acquisition_date)->toDateString() : null,
                'acquisition_cost' => $request->acquisition_cost,
                'funding_source' => $request->funding_source,
                'responsible_user_id' => $request->responsible_user_id,
                'is_active' => true,
                'qr_code_path' => null, // bisa digenerate nanti
            ]);

            Log::info('STEP 4: Asset berhasil dibuat', ['id' => $asset->id]);

            // Format isi QR Code
            $qrContent = url('/api/assets/' . $asset->id);

            // Generate QR Code dengan URL dokumen
            $options = new QROptions([
                'version'          => 10,
                'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'         => QRCode::ECC_L,
                'scale'            => 50,
                'imageBase64'      => false,
                'imageTransparent' => true,
            ]);

            $qrcode = (new QRCode($options))->render($qrContent);

            $qrFileName = 'qr-codes/' . $asset->asset_code . '.png';

            Log::info('STEP 5: Generate QR Code', ['qrContent' => $qrContent, 'fileName' => $qrFileName]);

            // Buat folder jika belum ada (tidak wajib, Laravel Storage handle ini otomatis saat menyimpan)
            if (!Storage::disk('public')->exists('qr-codes')) {
                Storage::disk('public')->makeDirectory('qr-codes');
            }

            // Simpan file hasil QR ke dalam storage/app/public/qr-codes
            Storage::disk('public')->put($qrFileName, $qrcode);

            // Update path di kolom qr_code_path
            $asset->update([
                'qr_code_path' => 'storage/' . $qrFileName
            ]);

            Log::info('STEP 6: Update QR Path berhasil');

            $this->logActivity('create', 'Asset', $asset->id, 'Menambahkan aset baru');

            Log::info('STEP 7: Log activity selesai, kirim response');

            return response()->json(['message' => 'Aset berhasil ditambahkan', 'data' => $asset, 'qr_code_url' => asset($asset->qr_code_path)], 201);
        } catch (Throwable $e) {
            Log::error('ERROR saat store asset:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Gagal menambah aset', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $asset = Asset::with(['category', 'type', 'condition', 'location', 'responsible'])->findOrFail($id);
            return response()->json($asset);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Aset tidak ditemukan', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $asset = Asset::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'asset_code' => 'required|unique:assets,asset_code,' . $id,
                'name' => 'required|string',
                'serial_number' => 'nullable|string',
                'category_id' => 'required|exists:asset_categories,id',
                'type_id' => 'nullable|exists:asset_types,id',
                'condition_id' => 'nullable|exists:asset_conditions,id',
                'location_id' => 'nullable|exists:asset_locations,id',
                'acquisition_date' => 'nullable|date',
                'acquisition_cost' => 'nullable|numeric',
                'funding_source' => 'nullable|string',
                'responsible_user_id' => 'nullable|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $asset->update($request->only([
                'asset_code',
                'name',
                'serial_number',
                'category_id',
                'type_id',
                'condition_id',
                'location_id',
                'acquisition_date',
                'acquisition_cost',
                'funding_source',
                'responsible_user_id'
            ]));

            return response()->json(['message' => 'Aset berhasil diperbarui', 'data' => $asset]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal update aset', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json(['message' => 'Aset berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus aset', 'error' => $e->getMessage()], 500);
        }
    }
}
