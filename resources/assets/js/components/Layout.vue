<template>
    <div>
        <!-- Navigation -->
        <nav class="navbar navbar-default">
            <div class="container">

                <div class="col-md-4 menu-block">
                    <!-- menu -->
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <router-link v-if="loggedIn" :to="{ name: 'containers.index' }">Containers</router-link>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <div class="logo-group text-center">
                        <img src="/logo.svg" alt="">
                    </div>
                </div>

                <div class="col-md-4 menu-block menu-block-right">
                    <!-- Auth menu -->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a v-if="loggedIn" @click.prevent='logOut()' href="#">Log out</a>
                            <router-link v-if="loggedIn === false" :to="{ name: 'account.auth.login' }">Log in
                            </router-link>
                        </li>
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
        data () {
            return {
                loggedIn: false,
            }
        },

        mounted() {
            this.initApiRestResourceService();
        },

        methods: {
            logOut() {
                // Remove the api key
                console.log(this.$cookie.get('api-token'));
                if (this.$cookie.get('api-token')) {
                    this.$cookie.delete('api-token');
                }

                // Forget the api token
                apiRestResourceService.forgetToken();

                // Redirect to login
                this.loggedIn = false;
                this.$router.push({name: 'account.auth.login'});
            },

            initApiRestResourceService() {
                let token = this.$cookie.get('api-token');

                // Set the api-token if present
                if (token !== undefined && token !== null) {
                    apiRestResourceService.setToken(this.$cookie.get('api-token'));
                    this.loggedIn = true;
                } else {
                    this.$router.push({name: 'account.auth.login'});
                }

            }
        }

    }
</script>

<style>
    .navbar {
        margin-bottom: 15px;
    }

    .navbar .container {
        display: flex;
    }

    .navbar .logo-group img {
        max-width: 225px;
        padding: 10px;
    }

    .navbar svg.logo .brand {
        fill: #2c3e50;
    }

    .navbar svg.logo .dots {
        fill: #2c3e50;
    }

    .menu-block {
        display: flex;
        align-items: center;
    }

    .menu-block-right {
        justify-content: flex-end
    }
</style>
