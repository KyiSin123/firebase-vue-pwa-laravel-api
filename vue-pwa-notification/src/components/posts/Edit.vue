<template>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Post</h4>
                </div>
                <div class="card-body">
                    <form @submit.prevent="update" enctype="multipart/form-data">
                        <div class="bg-success p-2 text-white text-center mb-3" v-if="success" role="alert">
                            {{ success }}
                        </div>
                        <div class="row">
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
                                <div class="form-group">
                                    <label>Choose Categories:</label>
                                    <div>
                                        <Multiselect
                                        v-model="form.categories" placeholder="Select options"
                                        :options="form.options" mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                        :create-option="true"
                                        :object="true"
                                        :resolve-on-load="false"
                                        :delay="0"
                                        :min-chars="1"
                                        />
                                    </div>
                                    <small class="text-danger" v-if="errors['categories.0']">
                                        {{  errors['categories.0'][0] }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label>Choose Image: </label>
                                    <input type="file" class="form-control" @change="onChange">
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
    import Multiselect from '@vueform/multiselect'
    import axios from "axios";
    import { onMounted, reactive, ref } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import Swal from 'sweetalert2';
    
    const router = useRoute();
    const router1 = useRouter();
    const router2 = useRoute();
    
    onMounted(() => {
        axios.get(`http://127.0.0.1:8000/api/post/edit/${router2.params.id}`, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response=>{
            form.title = response.data.post.title;
            form.options = response.data.categories;
            response.data.selectedCategories.forEach(category => {
                form.categories[i.value] = { value: category, label: category };
                i.value = i.value + 1;
            });
        });
    });
    
    const i = ref(0);
    const j = ref(0);
    let form = reactive({
        title: '',
        categories: [],
        file: '',
        options : '',
    });
    let category = ref([]);
    let errors = ref([]);
    let success = ref('');
    const onChange = (e) => {
        form.file = e.target.files[0];
    }

    const update = async() => {
        // if(navigator.onLine) {
        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        }
        let data = new FormData();
        data.append('title', form.title);
        data.append('file', form.file);
        for(j.value = 0; j.value < form.categories.length; j.value++) {
            category.value[j.value] = form.categories[j.value].value;
        }
        data.append('categories[]', category.value);
        await axios.post(`http://127.0.0.1:8000/api/post/edit/${router.params.id}`, data, config, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response=> {
            success.value = response.data;
            router1.push('/post/list');    
        }).catch(error => {
            if(error.response) {
                errors.value = error.response.data.errors;
            }
        })
    // } else {
        // Swal.fire('Warning!', 'You are currently offline. Please check your internet connection and try again.', 'info');
    // }
    }
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
