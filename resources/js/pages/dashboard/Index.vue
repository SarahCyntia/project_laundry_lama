<template>
  <div class="container py-4">
    <h1 class="mb-3">Dashboard</h1>

    <!-- Status Toko -->
    <div class="store-status-card mb-4">
      <div class="store-status-header">
        <i class="fas fa-store"></i>
        <h4>Status Toko</h4>
      </div>

      <!-- Alert untuk status berhasil diperbarui -->
      <div v-if="showSuccessAlert" class="alert alert-success">
        <button type="button" class="btn-close" @click="showSuccessAlert = false"></button>
        Status toko berhasil diperbarui.
      </div>

      <div :class="['current-status', storeStatus === 'buka' ? 'open' : 'closed']">
        <div :class="['status-indicator', storeStatus === 'buka' ? 'status-open' : 'status-closed']"></div>
        <div class="status-text">
          Toko sedang {{ storeStatus === 'buka' ? 'BUKA' : 'TUTUP' }}
        </div>
        <div v-if="statusTime" class="status-time">
          <i class="far fa-clock me-1"></i> Diperbarui: {{ statusTime }}
        </div>
      </div>

      <div class="status-actions">
        <button class="status-btn open-btn"
          :class="{ 'btn-disabled': storeStatus === 'buka' }"
          @click="updateStatus('buka')">
          <i class="fas fa-door-open"></i> Buka Toko
        </button>
        <button class="status-btn close-btn"
          :class="{ 'btn-disabled': storeStatus === 'tutup' }"
          @click="updateStatus('tutup')">
          <i class="fas fa-door-closed"></i> Tutup Toko
        </button>
      </div>

      <div class="status-info mt-3">
        <i class="fas fa-info-circle me-2"></i>
        Saat toko ditutup, pelanggan tidak akan dapat menggunakan layanan antar jemput cucian dan akan melihat pemberitahuan di halaman utama.
      </div>
    </div>

    <!-- Cards -->
    <div class="row g-4">
      <div class="col-md-3" v-for="card in cards" :key="card.title">
        <div class="card-dashboard h-100">
          <i :class="card.icon"></i>
          <h5>{{ card.title }}</h5>
          <p>{{ card.value }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";

const pesananHariIni = ref(0);
const pesananSelesai = ref(0);
const pemasukanHariIni = ref("Rp 0");
const antarJemputMenunggu = ref(0);
const storeStatus = ref("buka");
const statusTime = ref("");

const showSuccessAlert = ref(false);

const fetchData = async () => {
  try {
    const { data } = await axios.get("/data");
    pesananHariIni.value = data.pesananHariIni;
    pesananSelesai.value = data.pesananSelesai;
    pemasukanHariIni.value = data.pemasukanHariIniFormatted;
    antarJemputMenunggu.value = data.antarJemputMenunggu;
    storeStatus.value = data.storeStatus;
    // statusTime.value = data.statusTime;
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const updateStatus = async (status: string) => {
  if (storeStatus.value === status) return;

  try {
    console.log('Updating status to:', status);
    const { data } = await axios.post("/update-status", { status });

    // langsung update state dari response
    storeStatus.value = data.status;
    statusTime.value = data.statusTime;

    showSuccessAlert.value = true;
    setTimeout(() => {
      showSuccessAlert.value = false;
    }, 3000);

    // opsional: kalau mau sync lagi dengan database
    // await fetchData();
  } catch (error) {
    console.error("Error updating status:", error);
  }
};


let interval: any = null;

onMounted(() => {
  fetchData();
  interval = setInterval(fetchData, 3000); // update tiap 3 detik
});

onUnmounted(() => {
  clearInterval(interval);
});

const cards = [
  { title: "Pesanan Hari Ini", icon: "fas fa-box", value: pesananHariIni },
  { title: "Pesanan Selesai", icon: "fas fa-check-circle", value: pesananSelesai },
  { title: "Pemasukan Hari Ini", icon: "fas fa-wallet", value: pemasukanHariIni },
  { title: "Antar Jemput Menunggu", icon: "fas fa-truck", value: antarJemputMenunggu },
];
</script>

<style scoped>
/* Alert Styles */
.alert {
  padding: 12px 16px;
  margin-bottom: 20px;
  border-radius: 8px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.alert-success {
  background-color: rgba(212, 237, 218, 0.9);
  border: 1px solid rgba(198, 233, 202, 0.9);
  color: #0f5132;
}

.btn-close {
  background: none;
  border: none;
  font-size: 18px;
  color: inherit;
  cursor: pointer;
  opacity: 0.7;
  padding: 0;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-close:hover {
  opacity: 1;
}

.btn-close::before {
  content: '×';
  font-size: 20px;
  line-height: 1;
}

/* Store Status Card */
.container {
  max-width: 1200px;
}

/* Store Status Card */
.store-status-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  color: white;
  padding: 25px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.store-status-card::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 200%;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transform: rotate(45deg);
  animation: shimmer 3s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%) rotate(45deg); }
  100% { transform: translateX(200%) rotate(45deg); }
}

.store-status-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.store-status-header i {
  font-size: 24px;
}

.store-status-header h4 {
  margin: 0;
  font-weight: 600;
}

.current-status {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 12px;
  padding: 20px;
  margin: 20px 0;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  text-align: center;
  transition: all 0.3s ease;
}

.current-status.open {
  background: rgba(40, 167, 69, 0.2);
  border-color: rgba(40, 167, 69, 0.3);
}

.current-status.closed {
  background: rgba(220, 53, 69, 0.2);
  border-color: rgba(220, 53, 69, 0.3);
}

.status-indicator {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  margin: 0 auto 10px;
  position: relative;
  animation: pulse 2s infinite;
}

.status-open {
  background: #28a745;
  box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
}

.status-closed {
  background: #dc3545;
  box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
}

@keyframes pulse {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
  }
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
  }
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
  }
}

.status-text {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.status-time {
  font-size: 14px;
  opacity: 0.8;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

.status-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin: 20px 0;
}

.status-btn {
  padding: 12px 24px;
  border: none;
  border-radius: 25px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: 14px;
  min-width: 140px;
  justify-content: center;
}

.open-btn {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.open-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.close-btn {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  color: white;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.close-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

/* Button States - Simplified */
.status-btn {
  padding: 12px 24px;
  border: none;
  border-radius: 25px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: 14px;
  min-width: 140px;
  justify-content: center;
  color: white;
}

.open-btn {
  background: linear-gradient(135deg, #28a745, #20c997);
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.close-btn {
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.status-btn:hover {
  transform: translateY(-2px);
}

.open-btn:hover {
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.close-btn:hover {
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

/* Disabled state - visual only */
.status-btn.btn-disabled {
  opacity: 0.5;
  pointer-events: none;
}

.status-info {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  padding: 15px;
  font-size: 14px;
  display: flex;
  align-items: center;
  border-left: 4px solid rgba(255, 255, 255, 0.3);
}

/* Dashboard Cards */
.card-dashboard {
  background: white;
  border-radius: 15px;
  padding: 30px 25px;
  text-align: center;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  border: none;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.card-dashboard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-dashboard:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.card-dashboard i {
  font-size: 48px;
  margin-bottom: 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  display: block;
}

.card-dashboard h5 {
  color: #495057;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 15px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-dashboard p {
  color: #2c3e50;
  font-size: 28px;
  font-weight: 700;
  margin: 0;
  line-height: 1.2;
}

/* Responsive Design */
@media (max-width: 768px) {
  .status-actions {
    flex-direction: column;
  }
  
  .status-btn {
    width: 100%;
  }
  
  .card-dashboard {
    margin-bottom: 20px;
  }
  
  .store-status-card {
    padding: 20px;
  }
  
  .status-text {
    font-size: 20px;
  }
}

/* Animation for card values */
.card-dashboard p {
  animation: countUp 0.5s ease-out;
}

@keyframes countUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Loading state */
.card-dashboard.loading {
  opacity: 0.7;
}

.card-dashboard.loading p {
  animation: pulse 1.5s infinite;
}

/* Success/Error states for status updates */
.status-update-success {
  animation: flash-success 0.5s ease-in-out;
}

@keyframes flash-success {
  0%, 100% { background-color: transparent; }
  50% { background-color: rgba(40, 167, 69, 0.2); }
}

/* Utility classes */
.me-1 { margin-right: 0.25rem; }
.me-2 { margin-right: 0.5rem; }
.mb-3 { margin-bottom: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }
.mt-3 { margin-top: 1rem; }
.py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
.g-4 > * { padding: 1.5rem; }
.h-100 { height: 100%; }

.row {
  display: flex;
  flex-wrap: wrap;
  margin: -0.75rem;
}

.col-md-3 {
  flex: 0 0 25%;
  max-width: 25%;
  padding: 0.75rem;
}

@media (max-width: 768px) {
  .col-md-3 {
    flex: 0 0 100%;
    max-width: 100%;
  }
}
</style>