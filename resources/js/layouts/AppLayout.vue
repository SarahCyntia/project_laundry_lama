<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-white flex flex-col">
      <div class="px-6 py-4 font-bold text-xl border-b border-indigo-700">
        dPurpleLaundry
      </div>
      <nav class="flex-1 px-4 py-6 space-y-3">
        <RouterLink to="/" class="block p-2 rounded hover:bg-indigo-700">
          Dashboard
        </RouterLink>
        <RouterLink to="/orders" class="block p-2 rounded hover:bg-indigo-700">
          Orders
        </RouterLink>
        <RouterLink to="/customers" class="block p-2 rounded hover:bg-indigo-700">
          Customers
        </RouterLink>
        <RouterLink to="/services" class="block p-2 rounded hover:bg-indigo-700">
          Services
        </RouterLink>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
      <!-- Header -->
      <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span class="text-gray-600">Hello, Admin</span>
          <img
            src="https://ui-avatars.com/api/?name=Admin"
            alt="user"
            class="w-8 h-8 rounded-full"
          />
        </div>
      </header>

      <!-- Page -->
      <section class="p-6">
        <slot />
      </section>
    </main>
  </div>
</template>
