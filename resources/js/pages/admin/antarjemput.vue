<template>
  <div class="manajemen-wrapper">
    <!-- Header -->
    <div class="header">
      <h2>Manajemen Antar Jemput</h2>
      <span class="tanggal">{{ formatDate(new Date()) }}</span>
    </div>

    <!-- Status Layanan -->
    <div class="card status-card">
      <div class="status-info">
        <h3>Status Layanan Antar Jemput</h3>
        <p>
          Status Saat Ini:
          <span :class="isActive ? 'aktif' : 'nonaktif'">
            {{ isActive ? 'Layanan Aktif' : 'Layanan Nonaktif' }}
          </span>
        </p>
        <small class="update-time">
          Terakhir Diperbarui: {{ formatDate(updatedAt) }} oleh admin
        </small>
      </div>
      <div class="status-toggle">
        <label class="switch">
          <input type="checkbox" v-model="isActive" />
          <span class="slider round"></span>
        </label>
        <span class="status-label" :class="isActive ? 'aktif' : 'nonaktif'">
          {{ isActive ? 'AKTIF' : 'NONAKTIF' }}
        </span>
      </div>
    </div>

    <!-- Statistik -->
    <div class="stats-grid">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="card stat-card"
        :class="stat.color"
      >
        <div class="value">{{ stat.value }}</div>
        <div class="label">{{ stat.label }}</div>
      </div>
    </div>

    <!-- Filter -->
    <div class="card filter-card">
      <h3>Filter & Pencarian</h3>
      <div class="filter-btns">
        <button
          @click="showDeleted = false"
          :class="['btn', !showDeleted ? 'btn-primary' : 'btn-secondary']"
        >
          Data Aktif
        </button>
        <button
          @click="showDeleted = true"
          :class="['btn', showDeleted ? 'btn-danger' : 'btn-secondary']"
        >
          Data Terhapus ({{ stats[4].value }})
        </button>
      </div>

      <div class="filter-grid">
        <input
          type="text"
          placeholder="Tracking/Nama/No.HP"
          v-model="filters.search"
        />
        <input type="date" v-model="filters.dateStart" />
        <input type="date" v-model="filters.dateEnd" />
        <select v-model="filters.status">
          <option value="">Semua Status</option>
          <option value="menunggu">Menunggu</option>
          <option value="perjalanan">Perjalanan</option>
          <option value="selesai">Selesai</option>
        </select>
        <select v-model="filters.service">
          <option value="">Semua Layanan</option>
          <option value="antar">Antar</option>
          <option value="jemput">Jemput</option>
        </select>
      </div>

      <div class="filter-actions">
        <button class="btn btn-secondary" @click="resetFilters">Reset</button>
        <button class="btn btn-primary" @click="applyFilters">Filter</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// import { ref } from "vue";
import axios from "axios";
import { onMounted, ref, watch, type Ref } from "vue";
import Swal from "sweetalert2";
// import type { Input } from "@/types";
import { useDelete } from "@/libs/hooks";

const isActive = ref(true);
const updatedAt = ref(new Date());

const stats = ref([
  { label: "SEMUA PESANAN", value: 31, color: "border-sky" },
  { label: "MENUNGGU", value: 6, color: "border-yellow" },
  { label: "PERJALANAN", value: 5, color: "border-blue" },
  { label: "SELESAI", value: 20, color: "border-green" },
  { label: "TERHAPUS", value: 10, color: "border-red" },
]);
async function fetchStats() {
  try {
    const res = await axios.get("/antar-jemput"); // endpoint Laravel
    if (res.data.success) {
      stats.value = [
        { label: "SEMUA PESANAN", value: res.data.data.total, color: "border-sky" },
        { label: "MENUNGGU", value: res.data.data.menunggu, color: "border-yellow" },
        { label: "PERJALANAN", value: res.data.data.perjalanan, color: "border-blue" },
        { label: "SELESAI", value: res.data.data.selesai, color: "border-green" },
        { label: "TERHAPUS", value: res.data.data.terhapus, color: "border-red" },
      ];
    }
  } catch (e) {
    console.error("Gagal ambil statistik", e);
  }
}


const showDeleted = ref(false);

const filters = ref({
  search: "",
  dateStart: "",
  dateEnd: "",
  status: "",
  service: "",
});

function resetFilters() {
  filters.value = {
    search: "",
    dateStart: "",
    dateEnd: "",
    status: "",
    service: "",
  };
}

function applyFilters() {
  console.log("Filter diterapkan:", filters.value);
}

function formatDate(date: Date) {
  return date.toLocaleString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

onMounted(() => {
  fetchStats();
});
</script>
<!-- <script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";

const isActive = ref(true);
const updatedAt = ref(new Date());

const stats = ref<any[]>([]);
const showDeleted = ref(false);

const filters = ref({
  search: "",
  dateStart: "",
  dateEnd: "",
  status: "",
  service: "",
});

async function fetchData() {
  try {
    const res = await axios.get("/antar-jemput", {
      params: filters.value, // kirim filter ke backend
    });

    if (res.data.success) {
      isActive.value = res.data.data.is_active;
      updatedAt.value = res.data.data.updated_at;

      stats.value = [
        { label: "SEMUA PESANAN", value: res.data.data.total, color: "border-sky" },
        { label: "MENUNGGU", value: res.data.data.menunggu, color: "border-yellow" },
        { label: "PERJALANAN", value: res.data.data.perjalanan, color: "border-blue" },
        { label: "SELESAI", value: res.data.data.selesai, color: "border-green" },
        { label: "TERHAPUS", value: res.data.data.terhapus, color: "border-red" },
      ];
    }
  } catch (e) {
    console.error("Gagal ambil data", e);
  }
}

function resetFilters() {
  filters.value = {
    search: "",
    dateStart: "",
    dateEnd: "",
    status: "",
    service: "",
  };
  fetchData();
}

function applyFilters() {
  fetchData();
}

function formatDate(date: Date) {
  return new Date(date).toLocaleString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

onMounted(() => {
  fetchData();
});
</script> -->
<style scoped>
/* Wrapper */
.manajemen-wrapper {
  padding: 24px;
  font-family: "Segoe UI", sans-serif;
  color: #333;
  background: #f7f9fb;
}

/* Header */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.header h2 {
  font-size: 22px;
  font-weight: 700;
  color: #0284c7;
}
.tanggal {
  font-size: 13px;
  color: #666;
}

/* Card umum */
.card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Status */
.status-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.status-info h3 {
  font-weight: 600;
  font-size: 16px;
}
.status-info p {
  margin-top: 6px;
  font-size: 14px;
}
.status-info .aktif {
  color: #16a34a;
}
.status-info .nonaktif {
  color: #dc2626;
}
.update-time {
  color: #999;
  font-size: 12px;
}

/* Toggle switch */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch input {
  display: none;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 24px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #16a34a;
}
input:checked + .slider:before {
  transform: translateX(20px);
}
.status-label {
  font-size: 13px;
  margin-left: 10px;
  font-weight: 600;
}
.status-label.aktif {
  color: #16a34a;
}
.status-label.nonaktif {
  color: #dc2626;
}

/* Statistik */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 16px;
}
.stat-card {
  text-align: center;
  padding: 20px;
  border-top: 4px solid;
}
.stat-card .value {
  font-size: 22px;
  font-weight: 700;
}
.stat-card .label {
  font-size: 13px;
  margin-top: 4px;
  color: #555;
}
.border-sky {
  border-color: #0ea5e9;
}
.border-yellow {
  border-color: #facc15;
}
.border-blue {
  border-color: #3b82f6;
}
.border-green {
  border-color: #22c55e;
}
.border-red {
  border-color: #ef4444;
}

/* Filter */
.filter-card h3 {
  font-weight: 600;
  font-size: 16px;
  margin-bottom: 12px;
}
.filter-btns {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}
.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 12px;
}
.filter-grid input,
.filter-grid select {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 8px 10px;
  font-size: 14px;
}
.filter-actions {
  margin-top: 15px;
  display: flex;
  gap: 10px;
}
.btn {
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  border: none;
}
.btn-primary {
  background: #0ea5e9;
  color: #fff;
}
.btn-secondary {
  background: #e5e7eb;
  color: #333;
}
.btn-danger {
  background: #ef4444;
  color: #fff;
}
</style>
