export interface Order {
  id: number;
  order_number: string;
  customer_id: number;
  service_id: number;
  berat: number;
  total_harga: number;
  status: "pending" | "processing" | "ready" | "completed";
  perkiraan_selesai?: string | null;
  catatan?: string | null;
  created_at?: string;
  updated_at?: string;
}
export interface OrderInput {
  customer_id: number;
  service_id: number;
  berat: number;
  total_harga: number;
  status?: "pending" | "processing" | "ready" | "completed"; // default: pending
  perkiraan_selesai?: string | null;
  catatan?: string | null;
}
export interface UpdateOrderStatusInput {
  status: "pending" | "processing" | "ready" | "completed";
}       