<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetLoan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $loan->update($request->all());

        return response()->json(['message' => 'Data peminjaman diperbarui', 'data' => $loan]);
    }

    public function destroy($id)
    {
        $loan = AssetLoan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Data peminjaman dihapus']);
    }
}
