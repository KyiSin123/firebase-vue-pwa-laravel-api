<template>
    <div class="row m-5">
        <div class="col-6 form-width">
            <div class="card">
                <div class="card-header">
                    <h4>Create</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="store">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Title: </label>
                                    <input type="text" class="form-control" v-model="form.title">
                                    <small class="text-danger" v-if="errors.title">
                                        {{ errors.title[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="mb-3">
                                    <label for="Textarea" class="form-label">Content</label>
                                    <textarea v-model="form.body" class="form-control" id="Textarea" rows="3"></textarea>
                                    <small class="text-danger" v-if="errors.body">
                                        {{ errors.body[0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
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
    import axios from "axios";
    import { useRouter } from "vue-router";
    import { openDB } from 'idb';
    import Swal from 'sweetalert2';

    const router = useRouter();
    let form = reactive({
        id: Date.now().toString(),
        title: '',
        body: '',
    });

    let errors = ref([]);

    const store = async() => {
        try {
            await axios.post('http://127.0.0.1:8000/api/simple', form, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }).then(() =>{
                router.push('/list');
            });
        } catch(error) {
            if(error.message === 'Network Error') {
                storePendingData(form, 'POST');
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Post will be created when you are back to online',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                errors.value = error.response.data.errors;
            }
        }
    } 
    
    const storePendingData = async(data, method) => {
        const db = await openDB('offline-database', 1);
        
        const tx = db.transaction('pendingData', 'readwrite');
        tx.store.put({ ...data, method });
        await tx.done;
    }
</script>
<style scoped>
.form-width {
    margin: 0 auto;
}
</style>