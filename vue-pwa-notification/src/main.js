import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import axios from 'axios'
import { createPinia } from 'pinia'

const pinia = createPinia();

axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

if ('serviceWorker' in navigator) {
    navigator.serviceWorker
      .register('/customServiceWorker.js')
      .then(() => navigator.serviceWorker.ready)
      .then(registration => {
        if ('SyncManager' in window) {
          registration.sync.register('sync-posts');
        }
      })
      .catch(error => {
        console.error('Error registering service worker:', error);
      });
}
  
createApp(App).use(router, axios).use(pinia).mount('#app')
