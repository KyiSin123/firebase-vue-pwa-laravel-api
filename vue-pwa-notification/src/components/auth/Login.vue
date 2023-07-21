<template>
    <div class="row mt-5">
        <div class="col-5 form-width">
            <div class="card">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="login">
                        <div class="bg-danger p-2 text-white text-center mb-3" v-if="credentialsError" role="alert">
                            {{ credentialsError }}
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Email: </label>
                                    <input type="email" class="form-control" v-model="form.email">
                                    <small class="invalid-feedback" v-if="errors.email">
                                        {{  errors.email[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Password: </label>
                                    <input type="password" class="form-control" v-model="form.password">
                                    <small class="invalid-feedback" v-if="errors.password">
                                        {{  errors.password[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">Login</button>
                                <span> New User? </span> <router-link to="/register" class="text-decoration-none"> Create an account! </router-link>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from '@vue/reactivity';
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.store';
import { computed } from 'vue';

let form = reactive({
    email: '',
    password: '',
});
let errors = ref([]);
const router = useRouter()
const authStore = useAuthStore();
const credentialsError = computed(() => authStore.credentialsError);
const login = () => {
    errors.value = [];
    authStore.login(form).then(() => {
        if(credentialsError.value === null) {
            router.push('/');
        }
    }).catch(error => {
        if(error.response) {
            errors.value = error.response.data.errors;
        }
    }) 
}
</script>
<style scoped>
.form-width {
    margin: 0 auto;
}
.invalid-feedback {
    display: block;
}
</style>