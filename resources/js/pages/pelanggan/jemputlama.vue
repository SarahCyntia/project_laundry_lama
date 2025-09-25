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
         <i class="fas fa-truck-loading"></i> Antar Cucian
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
       <i class="fas fa-truck-pickup" id="ikon"></i>  <p id="text">Jemput Cucian</p> 
      </div>
      <div class="card-body p-9">
        <!-- Warning Batas Waktu -->
        <div class="alert alert-warning border-start border-warning border-4 mb-4" role="alert">
          <i class="fas fa-clock text-warning me-2"></i>
          <strong>Batas Waktu Penjemputan</strong><br>
          <small class="text-muted">
            Permintaan penjemputan yang dibuat setelah jam 17:00 akan diproses pada hari kerja berikutnya.
          </small>
        </div>

        <form @submit.prevent="kirimPermintaan">
          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nama Lengkap</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input
                v-model="namaLengkap"
                type="text"
                class="form-control fs-5"
                placeholder="Masukkan nama lengkap Anda"
                required
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nomor Telepon</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input
                v-model="nomorTelepon"
                type="text"
                class="form-control fs-5"
                placeholder="Masukkan nomor telepon Anda"
                required
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Alamat Penjemputan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              <textarea
                v-model="alamatPenjemputan"
                class="form-control fs-5"
                rows="3"
                placeholder="Isi alamat lengkap"
                required
              ></textarea>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Waktu Penjemputan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-clock"></i></span>
              <input
                v-model="waktuPenjemputan"
                type="datetime-local"
                class="form-control fs-5"
                required
              />
            </div>
            <small class="text-muted fs-6">
              Maksimal jam 17:00, akan diterima hari itu, akan diproses besok
            </small>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Catatan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
              <textarea
                v-model="catatan"
                class="form-control fs-5"
                rows="3"
                placeholder=""
              ></textarea>
            </div>
            <small class="text-muted fs-6">
              Tambahkan informasi tambahan jika diperlukan
            </small>
          </div>

          <button type="submit" class="btn btn-info text-white w-100 fs-5 py-3 fw-semibold">
            <i class="fas fa-paper-plane me-2"></i> Kirim Permintaan
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

    <!-- Modal Login -->
    <div v-if="showLoginModal" class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title">
              <i class="fas fa-sign-in-alt me-2"></i> Login Diperlukan
            </h5>
            <button type="button" class="btn-close btn-close-white" @click="showLoginModal = false"></button>
          </div>
          <div class="modal-body">
            <p class="mb-4">Anda harus login terlebih dahulu untuk mengirim permintaan penjemputan.</p>
            
            <form @submit.prevent="loginUser">
              <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input
                    v-model="loginUsername"
                    type="email"
                    class="form-control"
                    placeholder="Masukkan email"
                    required
                  />
                </div>
              </div>
              <!-- <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input
                    v-model="loginUsername"
                    type="text"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                  />
                </div>
              </div> -->

              <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input
                    v-model="loginPassword"
                    type="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                  />
                </div>
              </div>

              <button type="submit" class="btn btn-info text-white w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Login
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import axios from "@/libs/axios";
import { ref, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const currentRoute = computed(() => route.path);

// Form data
const namaLengkap = ref("");
const nomorTelepon = ref("");
const alamatPenjemputan = ref("");
const waktuPenjemputan = ref("");
const catatan = ref("");

// Login modal
const showLoginModal = ref(false);
const loginUsername = ref("");
const loginPassword = ref("");
const isLoggedIn = ref(false); // Simulasi status login

const kirimPermintaan = async () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }

  try {
   await axios.post("/penjemputan", {
  nama_pelanggan: namaLengkap.value,
  no_hp: nomorTelepon.value,
  alamat_jemput: alamatPenjemputan.value,
  waktu_jemput: waktuPenjemputan.value,
  catatan: catatan.value,
});

    alert("Permintaan penjemputan berhasil dikirim!");
    // reset form
    namaLengkap.value = "";
    nomorTelepon.value = "";
    alamatPenjemputan.value = "";
    waktuPenjemputan.value = "";
    catatan.value = "";
  } catch (err) {
    alert("Gagal mengirim permintaan, silakan coba lagi.");
  }
};


// const loginUser = async () => {
//   if (!loginUsername.value || !loginPassword.value) {
//     alert("Mohon isi email dan password!");
//     return;
//   }

//   try {
//     const res = await axios.post("login", {
//       email: loginUsername.value,
//       password: loginPassword.value,
//     });

//     if (res.data.success) {
//       isLoggedIn.value = true;
//       token.value = res.data.token; // simpan token untuk request selanjutnya
//       showLoginModal.value = false;
//       alert("Login berhasil! Sekarang Anda dapat mengirim permintaan.");

//       // Reset form login
//       loginUsername.value = "";
//       loginPassword.value = "";

//       // Otomatis submit form setelah login berhasil
//       setTimeout(() => {
//         kirimPermintaan();
//       }, 500);
//     }
//   } catch (err: any) {
//     alert(err.response?.data?.message || "Login gagal, coba lagi");
//   }
// };
</script>

<style scoped>
.pelanggan-area {
  background-color: #e6f7fa;
  /* min-height: 100vh; */
  padding-top: 80px; 
  padding-bottom: 60px;
  
}
#ikon {
  font-size: 1.1em;
  margin-right: 10px;
  margin-top: 13px;
  color: white;
}
#text {
  display: inline;
  vertical-align: middle;
  margin-top: 10px;
  margin-right: 77%;
}
</style>