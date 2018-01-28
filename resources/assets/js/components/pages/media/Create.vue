<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Container {{ $route.params.id }} - Add Media Item</h3>
                </div>

                <div class="panel-body">
                    <form @submit.prevent="createMedia()">

                        <!--File-->
                        <div class="form-group" v-bind:class="{ 'has-error': error.length }">
                            <label class="control-label" for="file">File</label>
                            <input id="file" type="file" name="file" ref="file" @change="setFile">
                            <span class="help-block">{{ error }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary pull-right">Upload</button>
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
                file : null,
                error: '',
            }
        },

        methods: {
            setFile(input) {
                this.file = input.target.files.length ? input.target.files[0] : null;
            },

            createMedia() {
                let id   = this.$route.params.id;
                let data = new FormData();
                data.append('media_item', this.file);

                apiRestResourceService.postUrl('/containers/' + id + '/media', data)
                    .then((res) => {

                        // Redirect to the containers page
                        this.$router.push({name: 'containers.media.index', params: {id: id}});
                    })
                    .catch((error) => {

                        // Check if name error present
                        if (error.response.data.errors.media_item[0] !== undefined) {
                            this.error = error.response.data.errors.media_item[0];
                        }
                    });
            }
        }

    }
</script>
