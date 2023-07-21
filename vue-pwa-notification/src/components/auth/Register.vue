<template>
    <div class="row mt-5">
        <div class="col-6 form-width">
            <div class="card">
                <div class="card-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="register">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Name: </label>
                                    <input type="text" class="form-control" v-model="form.name">
                                    <small class="text-danger" v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Email: </label>
                                    <input type="email" class="form-control" v-model="form.email" name="email">
                                    <small class="text-danger" v-if="errors.email">
                                        {{ errors.email[0] }}
                                    </small>                    
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Password: </label>
                                    <input type="password" class="form-control" v-model="form.password">
                                    <small class="text-danger" v-if="errors.password">
                                        {{ errors.password[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Confirm Password: </label>
                                    <input type="password" class="form-control" v-model="form.confirmPassword">
                                    <small class="text-danger" v-if="errors.confirmPassword">
                                        {{ errors.confirmPassword[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <span> Already have an account? </span> <router-link to="/login" class="text-decoration-none"> Login </router-link>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

let form = reactive({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
});

let errors = ref([]);
const router = useRouter();

const register = async() => {
    errors.value = [];
    try {
        await axios.post('http://127.0.0.1:8000/api/register', form).then(response=>{
            router.push('/login');
        });
    }
    catch(error) {
        errors.value = error.response.data.errors;
    }
}

</script>
<style scoped>
.form-width {
    margin: 0 auto;
}
</style>