import "./bootstrap";
// resources/js/app.js
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

import App from './App.vue'
import HomePage from './components/HomePage.vue'
import AboutPage from './components/AboutPage.vue'
import ServicesPage from './components/ServicesPage.vue'
import LocationPage from './components/LocationPage.vue'

// Setup axios
window.axios = axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Router setup
const routes = [
    { path: '/', name: 'Home', component: HomePage },
    { path: '/tentang-kami', name: 'About', component: AboutPage },
    { path: '/layanan', name: 'Services', component: ServicesPage },
    { path: '/lokasi', name: 'Location', component: LocationPage }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Create and mount app
const app = createApp(App)
app.use(router)
app.mount('#app')

// resources/js/App.vue
<template>
  <div id="app">
    <NavBar />
    <StatusBar />
    <router-view />
  </div>
</template>

<script>
import NavBar from './components/NavBar.vue'
import StatusBar from './components/StatusBar.vue'

export default {
  name: 'App',
  components: {
    NavBar,
    StatusBar
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f8fafc;
}

#app {
  min-height: 100vh;
}
</style>