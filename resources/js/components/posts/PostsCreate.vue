<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'indexPost'}" class="btn btn-light">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Create new post</div>
            <div class="panel-body">
                <form v-on:submit="saveForm($event)">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Post slug</label>
                            <input type="text" v-model="post.slug" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Post title</label>
                            <input type="text" v-model="post.title" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Post excerpt</label>
                            <textarea v-model="post.excerpt" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Post body</label>
                            <textarea v-model="post.body" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <button class="btn btn-success">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                post: {
                    slug: '',
                    title: '',
                    excerpt: '',
                    body: ''
                }
            }
        },
        methods: {
            saveForm(e) {
                e.preventDefault();
                const app = this;
                const newPost = app.post;
                if (document.getElementById('app_token')) {
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
                }
                axios.post('/api/v1/posts', newPost)
                    .then(function (resp) {
                        app.$router.push({path: '/admin/posts'});
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your post");
                    });
            }
        }
    }
</script>

<style scoped>

</style>
