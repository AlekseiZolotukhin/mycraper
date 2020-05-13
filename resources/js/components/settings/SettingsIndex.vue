<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createSettings'}" class="btn btn-success">Add new website</router-link>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Site list</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Website</th>
                        <th>URL</th>
                        <th>Selectors</th>
                        <th class="actions">{{ message }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item, index in settings">
                        <td>{{ item.website }}</td>
                        <td>{{ item.url }}</td>
                        <td>{{ item.selectors }}</td>
                        <td class="actions">
                            <a href="#" class="btn btn-sm btn-info" v-on:click="scrape(item.id)">
                                Scrape
                            </a>
                            <router-link :to="{name: 'editSettings', params: {id: item.id}}" class="btn btn-sm btn-warning">
                                Edit
                            </router-link>
                            <a href="#" class="btn btn-sm btn-danger" v-on:click="deleteEntry(item.id, index)">
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
                progress: false,
                message: 'Loading...',
                settings: []
            }
        },
        mounted() {
            const app = this;
            if (document.getElementById('app_token')) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
            }
            axios.get('/api/v1/settings')
                .then(function (resp) {
                    app.message = '';
                    app.settings = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.message = '';
                    alert("Could not load settings");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    const app = this;
                    if (app.progress) {
                        return;
                    }
                    app.progress = true;
                    axios.delete('/api/v1/settings/' + id)
                        .then(function (resp) {
                            app.progress = false;
                            app.settings.splice(index, 1);
                        })
                        .catch(function (resp) {
                            app.progress = false;
                            alert("Could not delete the post");
                        });
                }
            },
            scrape(id) {
                const app = this;
                if (app.progress) {
                    return;
                }
                app.message = 'Scrapping...';
                app.progress = true;
                axios.post('/api/v1/settings/scrape/' + id)
                    .then(function (resp) {
                        app.progress = false;
                        app.message = 'done';
                        app.$router.push({path: '/admin/posts'});
                    })
                    .catch(function (resp) {
                        app.progress = false;
                        app.message = '';
                        alert("Could not scrape posts");
                    });
            }
        }
    }
</script>

<style scoped>

</style>
