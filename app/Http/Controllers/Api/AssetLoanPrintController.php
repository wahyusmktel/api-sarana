<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetLoan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetLoanPrintController extends Controller
{
    public function generate($id)
    {
        $loan = AssetLoan::with(['asset.category', 'borrower'])->findOrFail($id);

        // Buat nomor dokumen unik
        $loan->document_number = 'DOC-' . strtoupper(Str::random(8));

        // Format isi QR Code
        $qrContent = $loan->borrower->username . '|' . now();

        // Generate QR Code dengan URL dokumen
        $options = new QROptions([
            'version'          => 10,
            'outputType'       => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'         => QRCode::ECC_L,
            'scale'            => 5,
            'imageBase64'      => false,
            'imageTransparent' => true,
        ]);

        $barcode = (new QRCode($options))->render($qrContent);
        $qr = base64_encode($barcode);

        $pdf = Pdf::loadView('pdf.asset-loan', [
            'loan' => $loan,
            'qr' => $qr,
        ]);

        $filename = 'Surat_Keterangan_Peminjaman_Aset_' . strtoupper(Str::random(6)) . '.pdf';
        $path = 'asset-loan-documents/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Simpan ke DB
        $loan->document_name = $filename; // 💾 Simpan nama file
        $loan->document_path = 'storage/' . $path;
        $loan->save();

        return response()->json([
            'message' => 'Dokumen berhasil dibuat',
            'document_url' => asset('storage/' . $path),
            'document_number' => $loan->document_number
        ]);
    }
}
