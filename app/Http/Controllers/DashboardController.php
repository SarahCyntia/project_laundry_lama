<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard'); // Blade berisi <div id="app"></div>
    }

    public function getData()
    {
        // Pesanan hari ini
        $pesananHariIni = DB::table('pesanan')
            ->whereDate('waktu', today())
            ->count();

        $pesananSelesai = DB::table('pesanan')
            ->whereDate('waktu', today())
            ->where('status', 'selesai')
            ->count();

        $pemasukanPesanan = DB::table('pesanan')
            ->whereDate('waktu', today())
            ->where('status_pembayaran', 'sudah_dibayar')
            ->whereNull('deleted_at')
            ->sum('harga');

        $pemasukanAntarJemput = DB::table('antar_jemput')
            ->where('status', 'selesai')
            ->whereNull('deleted_at')
            ->whereDate(DB::raw('COALESCE(selesai_at, waktu_antar, waktu_jemput)'), today())
            ->sum(DB::raw('COALESCE(harga, 5000)'));

        $pemasukanHariIni = $pemasukanPesanan + $pemasukanAntarJemput;

        $antarJemputMenunggu = DB::table('antar_jemput')
            ->where('status', 'menunggu')
            ->whereNull('deleted_at')
            ->count();

        $storeStatus = DB::table('toko_status')->where('id', 1)->first();
        if (!$storeStatus) {
            DB::table('toko_status')->insert([
                'id' => 1,
                'status' => 'buka',
                'waktu' => now()
            ]);
            $storeStatus = (object) [
                'status' => 'buka',
                'waktu' => now()
            ];
        }

        return response()->json([
            'pesananHariIni' => $pesananHariIni,
            'pesananSelesai' => $pesananSelesai,
            'pemasukanHariIni' => $pemasukanHariIni,
            'pemasukanHariIniFormatted' => "Rp " . number_format($pemasukanHariIni, 0, ',', '.'),
            'antarJemputMenunggu' => $antarJemputMenunggu,
            'storeStatus' => $storeStatus->status,
            'statusTime' => $storeStatus->waktu ? $storeStatus->waktu->format('d/m/Y H:i') : null,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $status = $request->input('status');
        DB::table('toko_status')->where('id', 1)->update([
            'status' => $status,
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status toko berhasil diperbarui",
            'status' => $status,
            'statusTime' => now()->format('d/m/Y H:i')
        ]);
    }
}
