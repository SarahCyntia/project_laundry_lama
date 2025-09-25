<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Penjemputan;
use Midtrans\Snap;
use Midtrans\Config;

class PenjemputanController extends Controller
{
    // Simpan permintaan jemput
    public function store(Request $request)
{
    $user = auth()->user();

    // $kode = 'AJ-' . str_pad(Penjemputan::count() + 1, 6, '0', STR_PAD_LEFT);
// misal di store()
$kode = 'AJ-' . date('Ymd') . '-' . str_pad(Penjemputan::count() + 1, 4, '0', STR_PAD_LEFT);

// Hasil contoh: AJ-20250922-0007

    $penjemputan = Penjemputan::create([
        'user_id' => $user->id,
        'nama_pelanggan' => $request->nama_pelanggan,
        'no_hp' => $request->no_hp,
        'alamat_jemput' => $request->alamat_jemput,
        'waktu_jemput' => $request->waktu_jemput,
        'catatan' => $request->catatan,
        'kode_tracking' => $kode,
        'status_pembayaran' => 'menunggu'
    ]);

    // Midtrans config
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = false; // sandbox dulu
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $penjemputan->id,
            'gross_amount' => 5000,
        ],
        'customer_details' => [
            'first_name' => $penjemputan->nama_pelanggan,
            'phone' => $penjemputan->no_hp,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    return response()->json([
        'success' => true,
        'kode_tracking' => $kode,
        'snap_token' => $snapToken,
    ]);
}
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'id_pelanggan'     => 'nullable|integer|exists:pelanggan,id', // opsional kalau sudah ada relasi
    //         'nama_pelanggan'   => 'required|string|max:100',
    //         'no_hp'            => 'required|string|max:15',
    //         'alamat_jemput'    => 'required|string',
    //         'waktu_jemput'     => 'required|date',
    //         'catatan'          => 'nullable|string',
    //     ]);

    //     // Generate tracking code unik
    //     do {
    //         $trackingCode = 'TRK-' . strtoupper(Str::random(8));
    //     } while (Penjemputan::where('tracking_code', $trackingCode)->exists());

    //     // Simpan ke database
    //     $penjemputan = Penjemputan::create([
    //         'id_pelanggan'   => $validated['id_pelanggan'] ?? null,
    //         'nama_pelanggan' => $validated['nama_pelanggan'],
    //         'no_hp'          => $validated['no_hp'],
    //         'alamat_jemput'  => $validated['alamat_jemput'],
    //         'waktu_jemput'   => $validated['waktu_jemput'],
    //         'tracking_code'  => $trackingCode,
    //         'layanan'        => 'jemput',   // default jemput
    //         'status'         => 'menunggu', // default status
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Permintaan jemput berhasil dibuat',
    //         'data'    => $penjemputan
    //     ]);
    // }
}
