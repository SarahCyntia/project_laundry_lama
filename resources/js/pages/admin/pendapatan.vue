<template>
  <div class="container my-10">
    <!-- ====== Ekspor PDF ====== -->
    <div class="card p-5 mb-10 shadow-sm">
      <h5 class="fw-bold mb-4">Ekspor PDF</h5>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Cabang</label>
          <select v-model="filtersPdf.branch" class="form-select">
            <option value="">Semua Cabang</option>
            <option v-for="c in branches" :key="c" :value="c">{{ c }}</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tanggal Awal</label>
          <input v-model="filtersPdf.start" type="date" class="form-control" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Tanggal Akhir</label>
          <input v-model="filtersPdf.end" type="date" class="form-control" />
        </div>
      </div>
      <div class="mt-4">
        <button class="btn btn-danger" @click="exportPdf">
          <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
        </button>
      </div>
    </div>

    <!-- ====== Laporan Pendapatan ====== -->
    <div class="card p-5 shadow-sm">
      <h5 class="fw-bold mb-4">Laporan Pendapatan Laundry</h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label">Cabang</label>
          <select v-model="filters.branch" class="form-select">
            <option value="">Semua Cabang</option>
            <option v-for="c in branches" :key="c" :value="c">{{ c }}</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Tanggal Awal</label>
          <input v-model="filters.start" type="date" class="form-control" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Tanggal Akhir</label>
          <input v-model="filters.end" type="date" class="form-control" />
        </div>
      </div>

      <div class="d-flex gap-2 mb-4">
        <button class="btn btn-success" @click="filterData">FILTER</button>
        <button class="btn btn-secondary" @click="resetFilter">RESET</button>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>TANGGAL</th>
              <th>LAYANAN PRIORITAS</th>
              <th>TOTAL BAYAR</th>
              <th>PENDAPATAN LAUNDRY</th>
              <th>PELANGGAN</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, i) in laporan" :key="i">
              <td>{{ row.tanggal }}</td>
              <td>{{ row.layanan }}</td>
              <td>{{ row.totalBayar }}</td>
              <td>{{ row.pendapatan }}</td>
              <td>{{ row.pelanggan }}</td>
            </tr>
            <tr v-if="laporan.length === 0">
              <td colspan="5" class="text-center text-muted">Tidak ada data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";

interface Laporan {
  tanggal: string;
  layanan: string;
  totalBayar: string;
  pendapatan: string;
  pelanggan: string;
}

export default defineComponent({
  setup() {
    const branches = ref(["Cabang A", "Cabang B"]);
    const filtersPdf = ref({
      branch: "",
      start: "2024-07-01",
      end: "2024-07-21",
    });
    const filters = ref({
      branch: "",
      start: "2024-07-01",
      end: "2024-07-21",
    });

    // contoh data dummy
    const laporan = ref<Laporan[]>([
      {
        tanggal: "01-07-2024",
        layanan: "Reguler",
        totalBayar: "Rp158.000,00",
        pendapatan: "Rp60.000,00",
        pelanggan: "Mutia Cinthia Prastuti",
      },
      {
        tanggal: "05-07-2024",
        layanan: "Reguler",
        totalBayar: "Rp158.000,00",
        pendapatan: "Rp60.000,00",
        pelanggan: "Nasim Atmaja Utama M.Ak",
      },
    ]);

    const exportPdf = () => {
      console.log("Ekspor PDF dengan filter:", filtersPdf.value);
      // Panggil API untuk generate PDF
    };

    const filterData = () => {
      console.log("Filter laporan:", filters.value);
      // Panggil API untuk filter laporan sesuai tanggal/cabang
    };

    const resetFilter = () => {
      filters.value = {
        branch: "",
        start: "",
        end: "",
      };
    };

    return {
      branches,
      filtersPdf,
      filters,
      laporan,
      exportPdf,
      filterData,
      resetFilter,
    };
  },
});
</script>

<style scoped>
.container {
  max-width: 1100px;
}
</style>
