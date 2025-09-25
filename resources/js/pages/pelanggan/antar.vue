<template>
  <div class="pelanggan-area py-5 ">
    <!-- Header -->
    <div class="text-center mb-5">
      <span class="badge bg-info text-white fs-6 px-7 py-2 mb-7 mt-10"> <i class="fas fa-user me-4"> </i> Area Pelanggan</span>
      <h1 class="fw-bold display-5">
        Selamat Datang di <span class="text-info">SLaundry</span>
      </h1>
      <p class="text-muted fs-5">
        Lacak status cucian Anda atau pesan layanan antar jemput dengan mudah
      </p>
    </div>

    <!-- Tabs -->
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-8">
      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan"
      >
        <i class="fas fa-search me-2"></i> Cek Status
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/riwayat' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/riwayat"
      >
        <i class="fas fa-history me-2"></i> Riwayat
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/antar' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/antar"
      >
        <i class="fas fa-truck-loading me-2"></i> Antar Cucian
      </router-link>

      <router-link
        class="btn btn-lg"
        :class="currentRoute === '/pelanggan/jemput' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/jemput"
      >
       <i class="fas fa-truck-pickup"></i> Jemput Cucian
      </router-link>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px" >
      <div class="card-header bg-info text-white fw-semibold fs-2 py-3">
       <i class="fas fa-truck-loading" id="ikon"></i> <p id="text">Antar Cucian</p> 
      </div>
      <div class="card-body p-9">
        <!-- Warning Batas Waktu -->
        <div class="alert alert-warning border-start border-warning border-4 mb-4" role="alert">
          <i class="fas fa-clock text-warning me-2"></i>
          <strong>Batas Waktu Pengantaran</strong><br>
          <small class="text-muted">
            Permintaan pengantaran yang dibuat setelah jam 17:00 akan diproses pada hari kerja berikutnya.
          </small>
        </div>

        <form @submit.prevent="cekKodeTracking">
          <div class="mb-6">
            <label class="form-label fw-semibold fs-5">Kode Tracking Pesanan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-barcode"></i></span>
              <input
                v-model="kodeTrackingPesanan"
                type="text"
                class="form-control fs-5"
                placeholder="Masukkan kode tracking pesanan yang sudah selesai"
                required
              />
            </div>
            <small class="text-muted fs-6">
              Hanya pesanan dengan status 'Selesai' yang dapat diantar
            </small>
          </div>

          <button type="submit" class="btn btn-info text-white w-100 fs-5 py-3 fw-semibold">
            <i class="fas fa-search me-2"></i> Cek Kode Tracking
          </button>
        </form>
      </div>
    </div>

    <!-- Back button -->
    <div class="mt-4 text-center">
      <router-link to="/beranda" class="btn btn-outline-info btn-lg px-4">
        ⬅️ Kembali ke Beranda
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const currentRoute = computed(() => route.path);

const kodeTrackingPesanan = ref("");

const cekKodeTracking = () => {
  if (!kodeTrackingPesanan.value) return;
  alert(`Mengecek kode tracking untuk antar cucian: ${kodeTrackingPesanan.value}`);
  // Di sini Anda bisa menambahkan logika untuk mengecek apakah pesanan sudah selesai
  // dan dapat diantar
};
</script>

<style scoped>
.pelanggan-area {
  background-color: #e6f7fa;
  /* min-height: 100vh; */
  padding-top: 80px; 
  padding-bottom: 60px; 
}
#ikon {
  font-size: 1.1em; /* Ukuran ikon */
  margin-right: 10px; /* Jarak antara ikon dan teks */
  margin-top: 13px;
  color: white;
}
#text {
  display: inline; /* Agar teks berada di sebelah ikon */
  vertical-align: middle; /* Agar teks sejajar secara vertikal dengan ikon */
  margin-top: 10px;
  margin-right: 80%;
}
</style>