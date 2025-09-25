export interface Customer {
  id: number;
  nama: string;
  no_hp: string;
  alamat: string;
  created_at?: string; // opsional kalau ikut dari backend
  updated_at?: string; // opsional kalau ikut dari backend
}
export interface CustomerInput {
  nama: string;
  no_hp: string;
  alamat: string;
}

