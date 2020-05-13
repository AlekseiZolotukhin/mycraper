<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'adminSettings'}" class="btn btn-light">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Create new target</div>
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
                        <div class="col-xs-12 form-group">
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
                target: {
                    website: '',
                    url: '',
                    selectors: '',
                    counter: ''
                }
            }
        },
        methods: {
            saveForm(e) {
                e.preventDefault();
                const app = this;
                const newTarget = app.target;
                if (document.getElementById('app_token')) {
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.getElementById('app_token').value;
                }
                axios.post('/api/v1/settings', newTarget)
                    .then(function (resp) {
                        app.$router.push({path: '/admin/settings'});
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your settings");
                    });
            }
        }
    }
</script>

<style scoped>

</style>
