<template>
    <div>
        <div class="form-group">
            <router-link to="/" class="btn btn-light">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Edit the target</div>
            <div class="panel-body">
                <form v-on:submit="saveForm($event)">
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Website</label>
                            <input type="text" v-model="target.website" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">URL</label>
                            <input type="text" v-model="target.url" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Selectors</label>
                            <textarea v-model="target.selectors" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label class="control-label">Counter</label>
                            <input type="number" min="1" v-model="target.counter" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            const app = this;
            const id = app.$route.params.id;
            app.targetId = id;
            if (document.getElementById('app_token')) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
            }
            axios.get('/api/v1/settings/' + id)
                .then(function (resp) {
                    app.target = resp.data;
                })
                .catch(function () {
                    alert("Could not load your company")
                });
        },
        data: function () {
            return {
                targetId: null,
                target: {
                    website: '',
                    url: '',
                    selectors: ''
                }
            }
        },
        methods: {
            saveForm(e) {
                e.preventDefault();
                const app = this;
                const newTarget = app.target;
                axios.patch('/api/v1/settings/' + app.targetId, newTarget)
                    .then(function (resp) {
                        app.$router.replace('/admin/settings');
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your target");
                    });
            }
        }
    }
</script>

<style scoped>

</style>
