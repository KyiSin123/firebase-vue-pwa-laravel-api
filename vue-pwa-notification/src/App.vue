<script setup>
import axios from "axios";
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import { onBeforeMount } from "vue";
import { useRouter } from "vue-router";
import Swal from 'sweetalert2'
import { useAuthStore } from '@/store/auth.store'
import { storeToRefs } from 'pinia'

const authStore = useAuthStore();
const { user: authUser } = storeToRefs(authStore);
const router = useRouter();
const logout = () => {
  authStore.logout()
  router.push('/login');
};

const firebaseConfig = {
  apiKey: "AIzaSyBptHdjnsvsUq1abqNRGi2ZsWEFJ92Psv8",
  authDomain: "vue-pwa-notification-f310e.firebaseapp.com",
  projectId: "vue-pwa-notification-f310e",
  storageBucket: "vue-pwa-notification-f310e.appspot.com",
  messagingSenderId: "27925344784",
  appId: "1:27925344784:web:42ed37a562ed5faf435095",
  measurementId: "G-35KVWCV9DC"
};

onBeforeMount(() => {
  initializeApp(firebaseConfig);
  const messaging = getMessaging();

  // Retrieve the device token
  getToken(messaging).then((token) => {
    axios.post('http://127.0.0.1:8000/api/token/store', {token}, {
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token')
      }
    })
  });

  onMessage(messaging, (payload) => {
    Swal.fire(payload.notification.title, payload.notification.body, 'info');
  });
})
</script>
<template>
  <nav class="navbar navbar-expand-sm navbar-dark bg-secondary">
      <div class="container">
          <div class="collapse navbar-collapse">
              <div class="navbar-nav py-2">
                  <router-link to="/" class="nav-item navbar-brand me-5 text-white">Home</router-link>
                  <router-link to="/post/list" class="nav-item nav-link text-white" v-if="authUser != null">Post List</router-link>
                  <router-link to="/list" class="nav-item nav-link text-white" v-if="authUser != null">Simple</router-link>
                  <div v-if="authUser != null" class="noti">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="notification-icon">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="notice-dot"></span>
                  </div>
                  <button @click="logout" v-if="authUser != null" class="btn text-white"> Logout </button>
                  <router-link to="/login" class="nav-item navbar-brand me-5 text-white" v-else>Login</router-link>
              </div>
          </div>
      </div>
  </nav>
  <router-view/>
</template>
<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

.noti {
  position: relative;
}

.notification-icon {
  width: 50px;
  height: 30px;
  color: white;
  padding: 4px 8px;
  margin-top: 6px;
}

.notice-dot {
  background-color: blue;
  padding: 4px 4px;
  border-radius: 50%;
  left: 27px;
  top: 9px;
  position: absolute;
}
</style>
