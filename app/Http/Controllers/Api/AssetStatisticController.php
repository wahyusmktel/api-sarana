<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class AssetStatisticController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'total_assets' => Asset::count(),
                'by_category' => Asset::select('category_id', DB::raw('count(*) as total'))
                    ->groupBy('category_id')
                    ->with('category:id,name')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'category_id' => $item->category_id,
                            'category_name' => optional($item->category)->name,
                            'total' => $item->total,
                        ];
                    }),

                'by_condition' => Asset::select('condition_id', DB::raw('count(*) as total'))
                    ->groupBy('condition_id')
                    ->with('condition:id,name')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'condition_id' => $item->condition_id,
                            'condition_name' => optional($item->condition)->name,
                            'total' => $item->total,
                        ];
                    }),

                'by_location' => Asset::select('location_id', DB::raw('count(*) as total'))
                    ->groupBy('location_id')
                    ->with('location:id,name')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'location_id' => $item->location_id,
                            'location_name' => optional($item->location)->name,
                            'total' => $item->total,
                        ];
                    }),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengambil statistik aset',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
