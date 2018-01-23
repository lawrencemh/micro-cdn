<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Containers
                        <router-link class="btn btn-success pull-right" :to="{ name: 'containers.create' }">
                            Create Container
                        </router-link>
                    </h3>
                </div>

                <div class="panel-body">
                    <table class="table">
                        <thead class="thead-inverse">
                            <tr>
                                <th width="width: 5%">#</th>
                                <th width="">Name</th>
                                <th width="">Created On</th>
                                <th width="">Updated On</th>
                                <th width="">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="(container, index) in containers">
                                <th scope="row">{{ container.id }}</th>
                                <td>{{ container.attributes.name }}</td>
                                <td>{{ container.attributes.created_at }}</td>
                                <td>{{ container.attributes.updated_at }}</td>
                                <td>
                                    <div class="btn-group">

                                        <router-link class="btn btn-default"
                                                     :to="{ name: 'containers.edit', params: { id: container.id } }">
                                            Edit
                                        </router-link>
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"
                                                class="btn btn-default dropdown-toggle">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <router-link
                                                        :to="{ name: 'containers.media.index', params: { id: container.id } }">
                                                    View
                                                </router-link>
                                            </li>
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
                containers: []
            }
        },

        mounted() {
            this.fetchContainersList();
        },

        methods: {
            fetchContainersList() {
                apiRestResourceService.getUrl('/containers')
                    .then((res) => {
                        this.containers = res.data.data
                    });
            }
        }

    }
</script>
