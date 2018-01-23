<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Media Items {{ $route.params.id }}
                        <router-link class="btn btn-success pull-right"
                                     :to="{ name: 'containers.media.create', params: { id: $route.params.id } }">
                            Add Item
                        </router-link>
                    </h3>
                </div>

                <div class="panel-body">
                    <table class="table">
                        <thead class="thead-inverse">
                            <tr>
                                <th width="width: 5%">#</th>
                                <th width="width: 20%">Name</th>
                                <th width="width: 15%">Thumbnail</th>
                                <th width="width: 20%">Created On</th>
                                <th width="width: 20%">Updated On</th>
                                <th width="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="(media, index) in mediaItems">
                                <th scope="row">{{ media.id }}</th>
                                <td>{{ media.attributes.name }}</td>
                                <td><img class="thumb" :src="media.attributes.small_path"></td>
                                <td>{{ media.attributes.created_at }}</td>
                                <td>{{ media.attributes.updated_at }}</td>
                                <td>
                                    <div class="btn-group">

                                        <router-link class="btn btn-default"
                                                     :to="{ name: 'containers.media.update', params: { containerId: $route.params.id , mediaId: media.id } }">
                                            Edit
                                        </router-link>
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"
                                                class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">View</a></li>
                                            <li><a href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</template>
<script>
    export default {
        data () {
            return {
                mediaItems: []
            }
        },

        mounted() {
            this.fetchMediaItemsList();
        },

        methods: {
            fetchMediaItemsList() {
                let id = this.$route.params.id;

                apiRestResourceService.getUrl('/containers/' + id + '/media/')
                    .then((res) => {
                        this.mediaItems = res.data.data;
                    });
            }
        }

    }
</script>
<style>
    img.thumb {
        max-width: 100px;
        max-height: 100px;
    }
</style>
