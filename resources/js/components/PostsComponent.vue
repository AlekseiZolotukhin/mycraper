<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div v-if="posts.length === 0" id="noposts-info">{{ message}}</div>
                <div class="news-item" v-for="post of posts" >
                    <router-link :to="{name: 'showPost', params: {id: post.post_id}}">
                        <h1>{{ post.title }}</h1>
                    </router-link>
                    <div class="post-body" v-html="post.excerpt + '...'"></div>
                    <router-link :to="{name: 'showPost', params: {id: post.post_id}}" class="btn btn-info">
                        Read More
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                message: 'Loading...',
                posts: []
            }
        },
        mounted() {
            var app = this;
            axios.get('/api/v1/posts')
                .then(function (resp) {
                    app.posts = resp.data;
                    if (app.posts.length === 0) {
                        app.message = 'No posts for show';
                    }
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.message = 'Could not load posts';
                });
        }
    }
</script>

<style scoped>

</style>
