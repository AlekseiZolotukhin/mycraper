<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createPost'}" class="btn btn-success">Create new post</router-link>
            <router-link :to="{name: 'adminSettings'}" class="btn btn-primary">Scrape new post</router-link>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Posts list</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>Body</th>
                        <th>Url</th>
                        <th class="actions"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post, index in posts">
                        <td>{{ post.slug }}</td>
                        <td>{{ post.title }}</td>
                        <td v-html="post.body"></td>
                        <td><router-link :to="{name: 'showPost', params: {id: post.post_id}}">/posts/{{ post.post_id }}</router-link></td>
                        <td class="actions">
                            <router-link :to="{name: 'editPost', params: {id: post.post_id}}" class="btn btn-sm btn-warning">
                                Edit
                            </router-link>
                            <a href="#" class="btn btn-sm btn-danger" v-on:click="deleteEntry(post.post_id, index)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                posts: []
            }
        },
        mounted() {
            const app = this;
            if (document.getElementById('app_token')) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
            }
            axios.get('/api/v1/posts')
                .then(function (resp) {
                    app.posts = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load posts");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    const app = this;
                    axios.delete('/api/v1/posts/' + id)
                        .then(function (resp) {
                            app.posts.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete post");
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>
