<!-- <template>
  <div class="container">
    <div class="row justify-content-end">
    </div>
    <div class="col-12 form-width">
        <div class="card">
            <div class="card-header">
                <h4>Notifications</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
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
</template>

<script>
export default {

}
</script>

<style>

</style> -->