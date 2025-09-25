<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AntarJemputController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::query();

        // Filter pencarian
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('tracking_code', 'like', "%{$request->search}%")
                  ->orWhereHas('pelanggan', function ($sub) use ($request) {
                      $sub->where('nama', 'like', "%{$request->search}%")
                          ->orWhere('no_hp', 'like', "%{$request->search}%");
                  });
            });
        }

        // Filter tanggal
        if ($request->dateStart && $request->dateEnd) {
            $query->whereBetween('created_at', [$request->dateStart, $request->dateEnd]);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter jenis layanan
        if ($request->service) {
            $query->where('jenis_layanan', $request->service);
        }

        // Ambil data untuk statistik
        $total      = $query->count();
        $menunggu   = $query->clone()->where('status', 'menunggu')->count();
        $perjalanan = $query->clone()->where('status', 'perjalanan')->count();
        $selesai    = $query->clone()->where('status', 'selesai')->count();
        $terhapus   = Pesanan::onlyTrashed()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'is_active' => true, // misal ambil dari setting
                'updated_at' => now(),
                'total' => $total,
                'menunggu' => $menunggu,
                'perjalanan' => $perjalanan,
                'selesai' => $selesai,
                'terhapus' => $terhapus,
            ]
        ]);
    }
}
