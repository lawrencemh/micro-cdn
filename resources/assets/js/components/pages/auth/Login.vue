<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Login</h3>
                </div>

                <div class="panel-body">

                    <form @submit.prevent="login()">

                        <!-- Api Token -->
                        <div class="form-group" v-bind:class="{ 'has-error': error.length }">
                            <label class="control-label" for="api-token">Api Token</label>
                            <input name="api-token" type="text" v-model.trim="apiToken" class="form-control"
                                   id="api-token"
                                   placeholder="api-token">
                            <span class="help-block">{{ error }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary pull-right">Login</button>
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
                apiToken: '',
                error   : '',
            }
        },

        mounted() {
            //
        },

        methods: {
            login() {
                axios.post('/account/auth/login', {'api-token': this.apiToken})
                    .then((res) => {

                        this.successfulLogin(res.data.token);
                    })
                    .catch((err) => this.loginFailed(err));
            },

            loginFailed(error) {
                switch (error.response.status) {

                    // Unauthorized
                    case 401:
                        this.error = "The api-token was rejected by the server";
                        break;

                    // Invalid
                    case 422:
                        this.error = "The api-token is not a valid format";
                        break;

                    // Unhandled error
                    default:
                        this.error = "Something went wrong trying to process this request";
                }
            },

            successfulLogin() {
                this.error = '';
                this.$cookie.set('api-token', this.apiToken, {expires: '30m'});
                this.$router.push({name: 'containers.index'});
            },
        }

    }
</script>
