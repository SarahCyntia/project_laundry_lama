export interface Service {
  id: number;
  nama: string;
  deskripsi?: string | null;
  harga_per_kg: number;
  durasi_jam: number;
  created_at?: string;
  updated_at?: string;
}
export interface ServiceInput {
  nama: string;
  deskripsi?: string | null;
  harga_per_kg: number;
  durasi_jam: number;
}
export interface ServiceUpdate {
  nama?: string;
  deskripsi?: string | null;
  harga_per_kg?: number;
  durasi_jam?: number;
}