import { defineStore } from 'pinia';
import axios from 'axios';
import { openDB, deleteDB } from 'idb';

axios.defaults.baseURL = 'http://localhost:8000/api';

async function deleteIndexedDB() {
    await deleteDB('auth-database');
}

export const useAuthStore = defineStore({
    id: 'auth',
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')),
        credentialsError: null,
    }),
    actions: {
        async login(credentials) {
            const response = await axios.post("/login", credentials);
            if(response.data.success === true) {
                const token = response.data.token;
                const db = await openDB('auth-database', 1, {
                    upgrade(db) {
                        if (!db.objectStoreNames.contains('auth')) {
                            db.createObjectStore('auth');
                        }
                    },
                });
                const tx = db.transaction('auth', 'readwrite');
                const store = tx.objectStore('auth');
                store.put(token, 'token');
                await tx.done;
                const user = response.data.user;
                this.user = user;
                localStorage.setItem('user', JSON.stringify(user));
                localStorage.setItem('token', response.data.token);                    
                this.credentialsError = null;
            } else {
                this.credentialsError = response.data.message;
            }
        },
            
        async logout() {
            this.user = null;
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            await deleteIndexedDB();
        }
    }
});
