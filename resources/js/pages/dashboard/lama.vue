<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

const stats = ref({
  totalOrders: 0,
  pendingOrders: 0,
  completedOrders: 0,
  customers: 0,
});

onMounted(async () => {
  const res = await axios.get("/api/dashboard");
  stats.value = res.data;
});
</script>

<template>
  <AppLayout>
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white shadow rounded-2xl p-6">
        <p class="text-gray-500">Total Orders</p>
        <h2 class="text-2xl font-bold">{{ stats.totalOrders }}</h2>
      </div>
      <div class="bg-white shadow rounded-2xl p-6">
        <p class="text-gray-500">Pending Orders</p>
        <h2 class="text-2xl font-bold text-yellow-600">
          {{ stats.pendingOrders }}
        </h2>
      </div>
      <div class="bg-white shadow rounded-2xl p-6">
        <p class="text-gray-500">Completed Orders</p>
        <h2 class="text-2xl font-bold text-green-600">
          {{ stats.completedOrders }}
        </h2>
      </div>
      <div class="bg-white shadow rounded-2xl p-6">
        <p class="text-gray-500">Customers</p>
        <h2 class="text-2xl font-bold text-indigo-600">
          {{ stats.customers }}
        </h2>
      </div>
    </div>

    <!-- Order Table -->
    <div class="mt-8 bg-white shadow rounded-2xl p-6">
      <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
      <table class="w-full text-left">
        <thead class="text-gray-600 border-b">
          <tr>
            <th class="p-2">Invoice</th>
            <th class="p-2">Customer</th>
            <th class="p-2">Service</th>
            <th class="p-2">Status</th>
            <th class="p-2">Created</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="order in [1, 2, 3]"
            :key="order"
            class="border-b hover:bg-gray-50"
          >
            <td class="p-2">INV-00{{ order }}</td>
            <td class="p-2">Customer {{ order }}</td>
            <td class="p-2">Laundry Service</td>
            <td class="p-2">
              <span
                class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700"
              >
                Pending
              </span>
            </td>
            <td class="p-2">2025-09-08</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
