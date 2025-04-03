<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            // Ambil hanya ID dan nama/username saja
            $users = User::select('id', 'username', 'email')->orderBy('username')->get();

            return response()->json($users);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil data users',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
