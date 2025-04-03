<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AssetDocument;
use Illuminate\Support\Facades\Log;
use Throwable;

class AssetDocumentController extends Controller
{
    public function index($id)
    {
        $documents = AssetDocument::where('asset_id', $id)->get();

        $documents->transform(function ($doc) {
            return [
                'id' => $doc->id,
                'asset_id' => $doc->asset_id,
                'file_name' => $doc->file_name,
                'file_path' => $doc->file_path,
                'document_type' => $doc->document_type,
                'uploaded_at' => $doc->uploaded_at,
                'url' => asset($doc->file_path), // 🔥 Tambahkan baris ini!
            ];
        });

        return response()->json(['data' => $documents]);
    }

    public function store(Request $request)
    {
        Log::info('Upload Dokumen Request:', $request->all());
        try {
            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|exists:assets,id',
                'document_type' => 'required|string',
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('asset-documents', $filename, 'public');

            $document = AssetDocument::create([
                'id' => Str::uuid(),
                'asset_id' => $request->asset_id,
                'document_type' => $request->document_type,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => 'storage/' . $path,
                'uploaded_at' => now(),
            ]);

            return response()->json([
                'message' => 'Dokumen berhasil diunggah',
                'data' => $document
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal upload dokumen', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $document = AssetDocument::findOrFail($id);

            if (Storage::disk('public')->exists(str_replace('storage/', '', $document->file_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $document->file_path));
            }

            $document->delete();

            return response()->json(['message' => 'Dokumen berhasil dihapus']);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Gagal menghapus dokumen', 'error' => $e->getMessage()], 500);
        }
    }

    // public function showByAsset($id)
    // {
    //     $documents = AssetDocument::where('asset_id', $id)->get();

    //     $documents->transform(function ($doc) {
    //         return [
    //             'id' => $doc->id,
    //             'asset_id' => $doc->asset_id,
    //             'file_name' => $doc->file_name,
    //             'file_path' => $doc->file_path,
    //             'document_type' => $doc->document_type,
    //             'uploaded_at' => $doc->created_at->format('Y-m-d H:i'),
    //             'url' => asset($doc->file_path), // 💥 Tambahkan ini bro!
    //         ];
    //     });

    //     return response()->json([
    //         'data' => $documents
    //     ]);
    // }

    public function showByAsset($id)
    {
        try {
            $documents = AssetDocument::where('asset_id', $id)->get();

            $documents = $documents->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'asset_id' => $doc->asset_id,
                    'file_name' => $doc->file_name,
                    'file_path' => $doc->file_path,
                    'document_type' => $doc->document_type,
                    'uploaded_at' => $doc->uploaded_at,
                    'url' => $doc->file_path ? asset($doc->file_path) : null,
                ];
            });

            return response()->json(['data' => $documents]);
        } catch (\Throwable $e) {
            Log::error('Gagal ambil dokumen:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan saat mengambil dokumen', 'error' => $e->getMessage()], 500);
        }
    }
}
