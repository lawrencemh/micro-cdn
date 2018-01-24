<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Edit Container #{{ $route.params.id }}</h3>
                </div>

                <div class="panel-body">
                    <form @submit.prevent="updateContainer()">

                        <!-- Name -->
                        <div class="form-group" v-bind:class="{ 'has-error': error.length }">
                            <label class="control-label" for="name">Name</label>
                            <input name="name" type="text" v-model.trim="name" class="form-control"
                                   id="aname"
                                   placeholder="Name">
                            <span class="help-block">{{ error }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</template>
<script>
    export default {
        data () {
            return {
                id   : null,
                name : '',
                error: '',
            }
        },

        mounted() {
            this.fetchContainer();
        },

        methods: {
            fetchContainer() {
                let id = this.$route.params.id;

                apiRestResourceService.getUrl('/containers/' + id)
                    .then((res) => {
                        this.id   = res.data.data.id;
                        this.name = res.data.data.attributes.name;
                    });
            },

            updateContainer() {
                apiRestResourceService.patchUrl('/containers/' + this.id, {name: this.name})
                    .then((res) => {

                        // Redirect to the containers page
                        this.$router.push({name: 'containers.index'});
                    })
                    .catch((error) => {

                        // Check if name error present
                        if (error.response.data.errors.name[0] !== undefined) {
                            this.error = error.response.data.errors.name[0];
                        }
                    });
            }
        }

    }
</script>
