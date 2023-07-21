<template>
    <div class="container">
        <div class="row my-4 justify-content-between">
            <div class="d-flex col-lg-9 mb-4 form-width">
                <router-link :to='{name:"post_create"}' class="btn btn-primary">Create</router-link>
            </div>
            <div class="col-lg-3 col-md-5">
            <form @submit.prevent="getPosts">
                <div class="input-group">
                    <input class="form-control float-right" v-model="search" type="search"
                        placeholder="Search">
                </div>
            </form>
        </div>
            <div class="col-12 form-width">
                <div class="card">
                    <div class="card-header">
                        <h4>Posts</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Categories</th>
                                        <th> Image </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="posts.length > 0">
                                    <tr v-for="(post,key) in posts" :key="key">
                                        <td class="align-middle">{{ post.id }}</td>
                                        <td class="align-middle">{{ post.title }}</td>
                                        <td class="align-middle"> <button class="btn btn-sm btn-secondary me-1" v-for="(category,index) in post.categories" :key="index"> {{ category.name }} </button> </td>
                                        <td> 
                                            <img
                                                :src="`http://localhost:8000` + post.image" class="image"
                                            />
                                        </td>
                                        <td class="align-middle">
                                            <router-link :to='{name:"post_edit",params:{id:post.id}}' class="btn btn-success me-2">Edit</router-link>
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
            @update:modelValue="getPosts"
        />
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";
import axios from "axios";
import Swal from 'sweetalert2';

let posts = ref([]);
let page = ref(1);
let pageCount = ref();
let search = ref('');
onMounted(async() => {
    await getPosts();
});
const getPosts = async () => {
    const response = await axios.get('http://127.0.0.1:8000/api/post/list?page=' + page.value + '&search=' + search.value, {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }  
    });   
    posts.value = response.data.post.data;
    pageCount.value = response.data.page_count;
}

const deletePost = async(id) => {
    if(navigator.onLine) {
    if(confirm("Are you sure to delete?")) {
        await axios
        .post(`http://127.0.0.1:8000/api/post/delete/${id}`, {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }  
    })
        .then( (response) => {
            getPosts();
        }).catch(response => {
            console.log("error" . response);
        });        
    }
} else {
    Swal.fire('Warning!', 'You are currently offline. Please check your internet connection and try again.', 'info');
}
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