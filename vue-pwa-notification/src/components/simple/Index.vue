<template>
    <div class="container">
        <div class="row my-4 justify-content-between">
            <div class="d-flex col-lg-9 mb-4 form-width">
                <router-link :to='{name:"simple_create"}' class="btn btn-primary">Create</router-link>
            </div>
            <div class="col-lg-3 col-md-5">
        </div>
            <div class="col-12 form-width">
                <div class="card">
                    <div class="card-header">
                        <h4>Simple Posts</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="posts.length > 0">
                                    <tr v-for="(post,key) in posts" :key="key">
                                        <td class="align-middle">{{ post.title }}</td>
                                        <td class="align-middle">{{ post.body }} </td>                                        
                                        <td class="align-middle">
                                            <router-link :to='{name:"simple_edit",params:{id:post.id}}' class="btn btn-success me-2">Edit</router-link>
                                            <button type="button" @click="deletePost(post.id)" class="btn btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="4" align="center">No Posts Found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-pagination
            v-model="page"
            :pages="pageCount"
            :range-size="1"
            active-color="#DCEDFF"
            @update:modelValue="getIndex"
        />
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";
import axios from "axios";
import Swal from 'sweetalert2';
import { openDB } from "idb";

let posts = ref([]);
let page = ref(1);
let pageCount = ref();
let form = reactive({
    id : '',
})

onMounted(async() => {
    await getIndex();
});
const getIndex = async () => {
    const response = await axios.get('http://127.0.0.1:8000/api/simple?page=' + page.value , {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }  
    });   
    posts.value = response.data.post.data;
    pageCount.value = response.data.page_count;
}

const deletePost = async(id) => {
    form.id = id;
    if(confirm("Are you sure to delete?")) {
        await axios
        .delete(`http://127.0.0.1:8000/api/simple/${id}`, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(() => {
            getIndex();
        }).catch(error => {
            if(error.message === 'Network Error') {
                storeDeletingData(form, 'DELETE');
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Post will be deleted when you are back to online',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                console.log('error', error);
            }
        });        
    }
} 

const storeDeletingData = async(data, method) => {
    const db = await openDB('offline-deleted-posts-database', 1);
    
    const tx = db.transaction('deletingData', 'readwrite');
    tx.store.put({ ...data, method });
    await tx.done;
}
</script>
<style scoped>
.form-width {
    margin: 0 auto;
}
.image {
    width: 100px;
    height: 100px;
}
</style>