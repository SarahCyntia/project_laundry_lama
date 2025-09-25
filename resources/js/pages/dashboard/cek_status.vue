<template>
  <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6">
    <!-- Card -->
    <div class="bg-white shadow-md rounded-xl w-full max-w-lg p-6">
      <h2 class="text-xl font-bold text-center text-teal-600 mb-4">Cek Status Cucian</h2>

      <!-- Input Tracking -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Kode Tracking</label>
        <input
          v-model="trackingCode"
          type="text"
          placeholder="Masukkan kode tracking (ZL... / AJ...)"
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-400"
        />
      </div>

      <!-- Button -->
      <button
        @click="cekStatus"
        class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg"
      >
        🔍 Cek Status
      </button>

      <!-- Hasil -->
      <div v-if="result" class="mt-6 border-t pt-4">
        <h3 class="font-semibold text-gray-700">📦 Detail Pesanan</h3>
        <p><strong>Kode:</strong> {{ result.tracking_code }}</p>
        <p><strong>Status:</strong> {{ result.status }}</p>
        <p><strong>Pelanggan:</strong> {{ result.pelanggan?.nama }}</p>
        <p><strong>Paket:</strong> {{ result.paket?.nama }}</p>
        <p><strong>Harga:</strong> Rp {{ result.harga }}</p>
      </div>

      <!-- Error -->
      <div v-if="error" class="mt-4 text-red-600">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import axios from "axios";

const trackingCode = ref("");
const result = ref<any>(null);
const error = ref("");

const cekStatus = async () => {
  error.value = "";
  result.value = null;

  try {
    const res = await axios.get(`/api/cek-status/${trackingCode.value}`);
    result.value = res.data.data;
  } catch (e: any) {
    error.value = e.response?.data?.message || "Terjadi kesalahan";
  }
};
</script>
