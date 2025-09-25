<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JemputController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:15',
            'alamat_penjemputan' => 'required|string',
            'pilih_layanan_laundry' => 'required|string', // ✅ Sesuai dengan yang diexpect backend
            'jam_jemput' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        try {
            // Generate tracking code unik
            $trackingCode = $this->generateTrackingCode();
            
            // Mapping layanan value (jika perlu)
            $layananMap = [
                'daily_kiloan' => 'daily_kiloan',
                'cuci_setrika' => 'cuci_setrika', 
                'laundry_sepatu' => 'laundry_sepatu',
                'laundry_tas' => 'laundry_tas',
                'laundry_sarung_bantal' => 'laundry_sarung_bantal',
                'laundry_bed_cover' => 'laundry_bed_cover',
                'laundry_sprei' => 'laundry_sprei',
                'laundry_boneka' => 'laundry_boneka'
            ];
            
            $layananValue = $layananMap[$request->pilih_layanan_laundry] ?? $request->pilih_layanan_laundry;
            
            // Insert data ke tabel antar_jemput
            $insertData = [
                'tracking_code' => $trackingCode,
                'nama_pelanggan' => $request->nama_lengkap,
                'no_hp' => $request->nomor_telepon,
                'alamat_antar' => $request->alamat_penjemputan,
                'alamat_jemput' => $request->alamat_penjemputan, // sama dengan alamat antar
                'layanan' => 'jemput', // ✅ Auto-set ke "jemput" karena ini form jemput
                'layanan_laundry' => $request->pilih_layanan_laundry, // ✅ Simpan jenis laundry di field terpisah
                'status' => 'menunggu', // default status
                'selesai_at' => null, // akan diisi nanti saat selesai
                'waktu_antar' => $request->jam_jemput,
                'waktu_jemput' => $request->jam_jemput,
                'harga' => 5000.00, // default harga sesuai tabel
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            // Jika ada catatan, tambahkan ke data
            if ($request->filled('catatan')) {
                // Bisa disimpan di field terpisah atau digabung dengan alamat
                $insertData['alamat_antar'] .= ' (Catatan: ' . $request->catatan . ')';
            }

            // Insert ke database
            $insertId = DB::table('antar_jemput')->insertGetId($insertData);

            // Insert ke tabel antarjemput_status untuk tracking
            DB::table('antarjemput_status')->insert([
                'status' => 'active',
                'updated_at' => Carbon::now(),
                'updated_by' => 'system'
            ]);

            // Redirect dengan pesan sukses dan tracking code
            return response()->json([
                'success' => true,
                'message' => 'Permintaan jemput berhasil dikirim!',
                'tracking_code' => $trackingCode,
                'data' => [
                    'id' => $insertId,
                    'tracking_code' => $trackingCode,
                    'nama_pelanggan' => $request->nama_lengkap,
                    'status' => 'menunggu'
                ]
            ], 201);

        } catch (\Exception $e) {
            // Jika ada error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate tracking code unik
     */
    private function generateTrackingCode()
    {
        do {
            // Format: JC + tanggal + 4 digit random
            // Contoh: JC24092400001
            $date = date('ymd');
            $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $trackingCode = 'JC' . $date . $random;
            
            // Cek apakah tracking code sudah ada
            $exists = DB::table('antar_jemput')
                       ->where('tracking_code', $trackingCode)
                       ->exists();
                       
        } while ($exists);

        return $trackingCode;
    }

    /**
     * Show form jemput
     */
    public function create()
    {
        return view('jemput');
    }

    /**
     * Cek status berdasarkan tracking code
     */
    public function checkStatus($trackingCode)
    {
        $order = DB::table('antar_jemput')
                  ->where('tracking_code', $trackingCode)
                  ->first();

        if (!$order) {
            return response()->json(['error' => 'Kode tracking tidak ditemukan'], 404);
        }

        return response()->json([
            'tracking_code' => $order->tracking_code,
            'nama_pelanggan' => $order->nama_pelanggan,
            'status' => $order->status,
            'waktu_jemput' => $order->waktu_jemput,
            'harga' => $order->harga
        ]);
    }
}