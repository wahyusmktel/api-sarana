<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetLoan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssetLoanController extends Controller
{
    public function index()
    {
        $loans = AssetLoan::with(['asset', 'borrower'])->latest()->get();
        return response()->json(['data' => $loans]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'borrower_user_id' => 'required|exists:users,id',
            'purpose' => 'nullable|string',
            'loan_start' => 'required|date',
            'loan_end' => 'required|date|after_or_equal:loan_start',
            'status' => 'required|string|in:Dipinjam,Dikembalikan,Terlambat',
            'returned_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $loan = AssetLoan::create([
            'id' => Str::uuid(),
            ...$request->all(),
        ]);

        // 👉 Ambil relasi asset & user
        $loan->load(['asset.category', 'borrower']);

        // 👉 Buat nomor dokumen
        $loan->document_number = 'DOC-' . strtoupper(Str::random(8));

        // 👉 Generate QR Code tanda tangan
        $qrContent = $loan->borrower->username . '|' . now();

        $options = new QROptions([
            'version'          => 10,
            'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'         => QRCode::ECC_L,
            'scale'            => 5,
            'imageBase64'      => false,
            'imageTransparent' => true,
        ]);

        $qr = base64_encode((new QRCode($options))->render($qrContent));

        // 👉 Generate PDF
        $pdf = Pdf::loadView('pdf.asset-loan', [
            'loan' => $loan,
            'qr' => $qr,
        ]);

        $filename = 'Surat_Keterangan_Peminjaman_Aset_' . strtoupper(Str::random(6)) . '.pdf';
        $path = 'asset-loan-documents/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // 👉 Simpan nama file dan path ke database
        $loan->document_name = $filename;
        $loan->document_path = 'storage/' . $path;
        $loan->save();

        return response()->json(['message' => 'Data peminjaman ditambahkan', 'data' => $loan], 201);
    }

    public function update(Request $request, $id)
    {
        $loan = AssetLoan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'borrower_user_id' => 'required|exists:users,id',
            'purpose' => 'nullable|string',
            'loan_start' => 'required|date',
            'loan_end' => 'required|date|after_or_equal:loan_start',
            'status' => 'required|string|in:Dipinjam,Dikembalikan,Terlambat',
            'returned_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        // Data baru yang akan di-hash
        $newData = $request->only([
            'asset_id',
            'borrower_user_id',
            'purpose',
            'loan_start',
            'loan_end',
            'status',
            'returned_at',
            'notes',
        ]);

        // Buat hash dari data baru
        $newHash = md5(json_encode($newData));

        // Jika hash sama dengan hash lama, tidak perlu generate ulang PDF
        if ($loan->data_hash === $newHash) {
            Log::info('No changes detected, skipping PDF generation.');
            return response()->json(['message' => 'Tidak ada perubahan data', 'data' => $loan]);
        } else {
            Log::info('Changes detected, regenerating PDF.');
        }

        // Update data peminjaman
        $loan->update($newData);

        // Update hash di database
        $loan->data_hash = $newHash;

        // Load relasi asset & user
        $loan->load(['asset.category', 'borrower']);

        // Generate nomor dokumen baru
        $loan->document_number = 'DOC-' . strtoupper(Str::random(8));

        // Generate nama file baru
        $filename = 'Surat_Keterangan_Peminjaman_Aset_' . strtoupper(Str::random(6)) . '.pdf';
        $path = 'asset-loan-documents/' . $filename;

        // Generate ulang QR Code tanda tangan
        $qrContent = $loan->borrower->username . '|' . now();

        $options = new QROptions([
            'version'          => 10,
            'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'         => QRCode::ECC_L,
            'scale'            => 5,
            'imageBase64'      => false,
            'imageTransparent' => true,
        ]);

        $qr = base64_encode((new QRCode($options))->render($qrContent));

        // Generate ulang PDF
        $pdf = Pdf::loadView('pdf.asset-loan', [
            'loan' => $loan,
            'qr' => $qr,
        ]);

        // Simpan file PDF baru
        Storage::disk('public')->put($path, $pdf->output());

        // Perbarui nama file dan path di database
        $loan->document_name = $filename;
        $loan->document_path = 'storage/' . $path;
        $loan->save();

        return response()->json(['message' => 'Data peminjaman diperbarui', 'data' => $loan]);
    }

    public function destroy($id)
    {
        $loan = AssetLoan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Data peminjaman dihapus']);
    }
}
