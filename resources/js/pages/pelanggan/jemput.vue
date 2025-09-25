<template>
  <div class="pelanggan-area py-5">
    <!-- Header -->
    <div class="text-center mb-5">
      <span class="badge bg-info text-white fs-6 px-7 py-2 mb-7 mt-10">
        <i class="fas fa-user me-2"></i> Area Pelanggan
      </span>
      <h1 class="fw-bold display-5">
        Selamat Datang di <span class="text-info">SLaundry</span>
      </h1>
      <p class="text-muted fs-5">
        Lacak status cucian Anda atau pesan layanan antar jemput dengan mudah
      </p>
    </div>

    <!-- Tabs -->
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-8">
      <router-link class="btn btn-lg"
        :class="currentRoute === '/pelanggan' ? 'btn-info text-white' : 'btn-outline-info'" to="/pelanggan">
        <i class="fas fa-search me-2"></i> Cek Status
      </router-link>

      <router-link class="btn btn-lg"
        :class="currentRoute === '/pelanggan/riwayat' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/riwayat">
        <i class="fas fa-history me-2"></i> Riwayat
      </router-link>

      <router-link class="btn btn-lg"
        :class="currentRoute === '/pelanggan/antar' ? 'btn-info text-white' : 'btn-outline-info'" to="/pelanggan/antar">
        <i class="fas fa-truck-loading me-2"></i> Antar Cucian
      </router-link>

      <router-link class="btn btn-lg"
        :class="currentRoute === '/pelanggan/jemput' ? 'btn-info text-white' : 'btn-outline-info'"
        to="/pelanggan/jemput">
        <i class="fas fa-truck-pickup me-2"></i> Jemput Cucian
      </router-link>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px">
      <div class="card-header bg-info text-white fw-semibold fs-2 py-3">
        <i class="fas fa-truck-loading" id="ikon"></i>
        <p id="text">Jemput Cucian</p>
      </div>

      <div class="card-body p-9">
        <!-- Warning Batas Waktu -->
        <div class="alert alert-warning border-start border-warning border-4 mb-4" role="alert">
          <i class="fas fa-clock text-warning me-2"></i>
          <strong>Batas Waktu Penjemputan</strong><br />
          <small class="text-muted">
            Permintaan penjemputan yang dibuat setelah jam 17:00 akan diproses pada hari kerja berikutnya.
          </small>
        </div>

        <form @submit.prevent="kirimPermintaan">
          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nama Lengkap</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input v-model="namaLengkap" type="text" class="form-control fs-5"
                placeholder="Masukkan nama lengkap Anda" required />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Nomor Telepon</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input v-model="nomorTelepon" type="text" class="form-control fs-5"
                placeholder="Masukkan nomor telepon Anda" required />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Alamat Penjemputan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              <textarea v-model="alamatPenjemputan" class="form-control fs-5" rows="3" placeholder="Isi alamat lengkap"
                required></textarea>
            </div>
          </div>

          <div class="mb-4">
            <!-- <label class="form-label fw-semibold fs-5">Penjemputan</label> -->
            <div class="row g-3">
              <!-- Daily Kiloan -->
              <div class="col-md-4">
                <select v-model="layananLaundry" class="form-select form-select-lg" required>
                  <option disabled value="">Pilih Layanan Laundry</option>
                  <option value="daily_kiloan">Daily Kiloan</option>
                  <option value="cuci_setrika">Cuci dan Setrika</option>
                  <option value="laundry_sepatu">Laundry Sepatu </option>
                  <option value="laundry_tas">Laundry Tas</option>
                  <option value="laundry_sarung_bantal">Laundry Sarung Bantal</option>
                  <option value="laundry_bed_cover">Laundry Bed Cover </option>
                  <option value="laundry_sprei">Laundry Sprei </option>
                  <option value="laundry_boneka">Laundry Boneka </option>
                  <!-- <option value="express">Express</option> -->
                </select>
              </div>

              <!-- Tanggal Jemput -->
              <div class="col-md-4">
                <input v-model="tanggalJemput" type="date" class="form-control form-control-lg"
                  placeholder="Tanggal Jemput" required />
              </div>

              <!-- Jam Jemput -->
              <div class="col-md-4">
                <input v-model="jamJemput" type="text" class="form-control form-control-lg" placeholder="Jam Jemput"
                  ref="picker" required />
                <!-- <input v-model="jamJemput" type="time" class="form-control form-control-lg" placeholder="Jam Jemput"
                  required /> -->
              </div>
            </div>
            <small class="text-muted fs-6">
              Maksimal jam 17:00 akan diproses hari itu, lewat akan diproses besok
            </small>
          </div>
          <!-- <div class="mb-4">
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
              Maksimal jam 17:00 akan diproses hari itu, lewat akan diproses besok
            </small>
          </div> -->

          <div class="mb-4">
            <label class="form-label fw-semibold fs-5">Catatan</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
              <textarea v-model="catatan" class="form-control fs-5" rows="3"
                placeholder="Tambahkan catatan jika ada"></textarea>
            </div>
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
  </div>
</template>

<script setup lang="ts">
import axios from "@/libs/axios";
import { ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import Swal from "sweetalert2";
import { onMounted } from "vue";

import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.css'

const namaLengkap = ref("");
const nomorTelepon = ref("");
const alamatPenjemputan = ref("");
const jamJemput = ref("");
const catatan = ref("");
const layananLaundry = ref("");
const tanggalJemput = ref("");
const picker = ref(null);

const route = useRoute();
const router = useRouter();
const currentRoute = computed(() => route.path);

// Initialize flatpickr untuk time picker
onMounted(() => {
  if (picker.value) {
    flatpickr(picker.value, {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true
    });
  }
});

const kirimPermintaan = async () => {
  // Validasi form
  if (!namaLengkap.value || !nomorTelepon.value || !alamatPenjemputan.value || 
      !layananLaundry.value || !tanggalJemput.value || !jamJemput.value) {
    Swal.fire({
      icon: "error",
      title: "Form Tidak Lengkap",
      text: "Mohon lengkapi semua field yang wajib diisi.",
    });
    return;
  }

  try {
    // Gabungkan tanggal dan jam
    const waktuJemput = `${tanggalJemput.value} ${jamJemput.value}`;
    
    const response = await axios.post("/jemput", {
      nama_lengkap: namaLengkap.value,
      nomor_telepon: nomorTelepon.value,
      alamat_penjemputan: alamatPenjemputan.value,
      pilih_layanan_laundry: layananLaundry.value,
      jam_jemput: waktuJemput,
      catatan: catatan.value
    });

    console.log("Response:", response.data);

    // Tampilkan notifikasi sukses dengan tracking code
    await Swal.fire({
      icon: "success",
      title: "Permintaan Berhasil Dikirim!",
      html: `
        <p>Permintaan penjemputan Anda telah berhasil dikirim.</p>
        <p><strong>Kode Tracking:</strong> <span style="color: #17a2b8; font-weight: bold;">${response.data.tracking_code || 'Akan dikirim via SMS'}</span></p>
        <p><small>Simpan kode ini untuk melacak status cucian Anda.</small></p>
      `,
      confirmButtonText: "OK"
    });

    // Reset form setelah berhasil
    resetForm();

  } catch (error: any) {
    console.error("Error:", error.response?.data || error);
    
    // Handle auth error - redirect ke login
    if (error.response?.status === 401 || error.response?.status === 403) {
      await Swal.fire({
        icon: "warning",
        title: "Session Expired",
        text: "Silakan login kembali untuk melanjutkan.",
        confirmButtonText: "Login"
      });
      router.push("/sign-in");
      return;
    }
    
    let errorMessage = "Terjadi kesalahan saat mengirim permintaan.";
    
    if (error.response?.data?.message) {
      errorMessage = error.response.data.message;
    } else if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat();
      errorMessage = errors.join('\n');
    }

    Swal.fire({
      icon: "error",
      title: "Gagal Mengirim Permintaan",
      text: errorMessage,
    });
  }
};

// Function untuk reset form
const resetForm = () => {
  namaLengkap.value = "";
  nomorTelepon.value = "";
  alamatPenjemputan.value = "";
  layananLaundry.value = "";
  tanggalJemput.value = "";
  jamJemput.value = "";
  catatan.value = "";
};
</script>

<style scoped>
.pelanggan-area {
  background-color: #e6f7fa;
  padding-top: 80px;
  padding-bottom: 60px;
}

/* .pelanggan-area {
  background-color: #e6f7fa;
  padding-top: 80px; 
  padding-bottom: 60px; 
} */
#ikon {
  font-size: 1.1em;
  /* Ukuran ikon */
  margin-right: 10px;
  /* Jarak antara ikon dan teks */
  margin-top: 13px;
  color: white;
}

#text {
  display: inline;
  /* Agar teks berada di sebelah ikon */
  vertical-align: middle;
  /* Agar teks sejajar secara vertikal dengan ikon */
  margin-top: 10px;
  margin-right: 77%;
}
</style>
