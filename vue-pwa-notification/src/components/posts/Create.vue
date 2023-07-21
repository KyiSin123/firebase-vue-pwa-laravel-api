
<template>
    <div class="row m-5">
        <div class="col-6 form-width">
            <div class="card">
                <div class="card-header">
                    <h4>Create Post</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="create" enctype="multipart/form-data">
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
                                <div class="form-group">
                                    <label>Choose Categories: </label>
                                    <div>
                                        <Multiselect
                                        v-model="form.categories"
                                        :options="form.options" mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                        :create-option="true"
                                        />
                                    </div>
                                    <small class="text-danger" v-if="errors.categories">
                                        {{ errors.categories[0] }}
                                    </small>                    
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Choose Image: </label>
                                    <input type="file" class="form-control" @change="onChange">
                                    <small class="text-danger" v-if="errors.file">
                                        {{ errors.file[0] }}
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
    import Multiselect from '@vueform/multiselect'
    import { onBeforeMount, reactive, ref } from 'vue';
    import axios from "axios";
    import { useRouter } from "vue-router";
    import Swal from 'sweetalert2';

    onBeforeMount (() => {
        axios.get('http://127.0.0.1:8000/api/categories', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response=>{
            form.options = response.data;
        })
    })

    const router = useRouter();
    let form = reactive({
        title: '',
        categories: [],
        file: '',
        options : '',
    });

    let errors = ref([]);

    const onChange = (e) => {
        form.file = e.target.files[0];
    }

    const create = async() => {
        // if(navigator.onLine) {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            data.append('title', form.title);
            data.append('file', form.file);
            data.append('categories', form.categories);
            try {
                await axios.post('http://127.0.0.1:8000/api/post/create', data, config, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response=>{
                    router.push('/post/list');
                });
            } catch(error) {
                console.log('error', error);
                if(error.message === 'Network Error') {
                    // storePendingData(data, config, 'POST');
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
        // } else {
            // Swal.fire('Warning!', 'You are currently offline. Please check your internet connection and try again.', 'info');
        // }
    } 

    // const storePendingData = async(data, config, method) => {
    //     console.log('data', data);
    //     const db = await openDB('offline-database', 1);
        
    //     const tx = db.transaction('pendingData', 'readwrite');
    //     tx.store.put({ ...data, method });
    //     await tx.done;
    // }
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.form-width {
    margin: 0 auto;
}
</style>