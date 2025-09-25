// resources/js/stores/laundry.ts
import { defineStore } from "pinia";

export const useLaundryStore = defineStore("laundry", {
  state: () => ({
    customers: [] as any[],
    services: [] as any[],
    orders: [] as any[],
  }),

  actions: {
    setCustomers(data: any[]) {
      this.customers = data;
    },
    setServices(data: any[]) {
      this.services = data;
    },
    setOrders(data: any[]) {
      this.orders = data;
    },
  },
});
