// types/Jemput.ts

export interface Jemput {
  id?: number;
  id_pesanan?: number;
  id_pelanggan?: number;
  tracking_code: string;
  nama_pelanggan: string;
  no_hp: string;
  layanan: string;
  layanan_laundry?: string;
  alamat_antar: string;
  alamat_jemput: string;
  status: 'menunggu' | 'dikonfirmasi' | 'dijemput' | 'dicuci' | 'selesai' | 'dibatalkan';
  waktu_antar: string; // ISO date string atau format yang sesuai
  waktu_jemput: string; // ISO date string atau format yang sesuai
  selesai_at?: string | null; // ISO date string atau null
  harga: number;
  status_pembayaran?: 'belum_bayar' | 'lunas' | 'sebagian';
  created_at?: string;
  updated_at?: string;
}

// Type untuk form input
export interface JemputFormData {
  nama_pelanggan: string;
  no_hp: string;
  layanan: string;
  layanan_laundry?: string;
  alamat_antar: string;
  alamat_jemput: string;
  waktu_antar: string;
  waktu_jemput: string;
  harga?: number;
}

// Type untuk response API
export interface JemputResponse {
  success: boolean;
  message: string;
  data?: Jemput;
  tracking_code?: string;
}

// Type untuk update status
export interface JemputStatusUpdate {
  tracking_code: string;
  status: Jemput['status'];
  selesai_at?: string;
}

// Type untuk filter/search
export interface JemputFilter {
  status?: Jemput['status'];
  nama_pelanggan?: string;
  tracking_code?: string;
  tanggal_mulai?: string;
  tanggal_selesai?: string;
}