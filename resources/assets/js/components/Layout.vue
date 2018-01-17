<template>
    <div>
        <!-- Navigation -->
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="col-md-2">LOGO</div>
                <div class="col-md-10">

                    <!-- menu -->
                    <ul class="nav navbar-nav navbar-left">
                        <li><router-link :to="{ name: 'containers.index' }">Containers</router-link></li>
                    </ul>

                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <router-view></router-view>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        //
    }
</script>

<style>
    .navbar {
        margin-bottom: 15px;
    }
</style>
