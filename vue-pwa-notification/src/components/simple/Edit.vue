<template>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Simple Post</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update">
                        <div class="row">
                            <input type="hidden" class="form-control" v-model="form.id">
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Title</label>
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
    
<script setup>
    import axios from "axios";
    import { onMounted, reactive, ref } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import { openDB } from "idb";
    import Swal from "sweetalert2";
    
    const router = useRoute();
    const router1 = useRouter();
    const router2 = useRoute();
    
    onMounted(() => {
        axios.get(`http://127.0.0.1:8000/api/simple/${router2.params.id}`, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response=>{
            form.id = response.data.post.id;
            form.title = response.data.post.title;
            form.body = response.data.post.body;
        });
    });

    let form = reactive({
        id: '',
        title: '',
        body: '',
    });
    let errors = ref([]);
    let success = ref('');

    const update = async() => {
        await axios.patch(`http://127.0.0.1:8000/api/simple`, form, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response=> {
            success.value = response.data;
            router1.push('/list');    
        }).catch(error => {
            if(error.message === 'Network Error') {
                storePendingData(form, 'PATCH');
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Post will be updated when you are back to online',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                errors.value = error.response.data.errors;
            }
        })
    } 

    const storePendingData = async(data, method) => {
        const db = await openDB('offline-database', 1);
        const tx = db.transaction('pendingData', 'readwrite');
        tx.store.put({ ...data, method });
        await tx.done;
    }
</script>
