<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'posts'}" class="btn btn-light">Back to posts</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h1>{{ post.title }}</h1></div>
            <div class="panel-body news-detail-body" v-html="post.body"></div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            const app = this;
            const id = app.$route.params.id;
            app.postId = id;
            if (document.getElementById('app_token')) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
            }
            axios.get('/api/v1/posts/' + id)
                .then(function (resp) {
                    app.post = resp.data;
                })
                .catch(function () {
                    alert("Could not load post")
                });
        },
        data: function () {
            return {
                postId: null,
                post: {
                    slug: '',
                    title: '',
                    body: ''
                }
            }
        }
    }
</script>

<style scoped>

</style>
