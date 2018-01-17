<template>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3>Login</h3>
                </div>

                <div class="panel-body">

                    <form @submit.prevent="login()">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input name="email" type="email" v-model.trim="email" class="form-control" id="email"
                                   placeholder="Email">
                        </div>

                        <!-- password -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" v-model="password" class="form-control" id="password"
                                   placeholder="Password">
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
                email   : '',
                password: '',
            }
        },

        mounted() {
            //
        },

        methods: {
            login() {
                axios.post('/account/auth/login', {email: this.email, password: this.password})
                    .then((res) => {
                        this.$cookie.set('api-token', res.data.token, {expires: '30m'});
                        this.successfulLogin();
                    })
                    .catch((err) => console.error(err));
            },

            successfulLogin() {
                this.$router.push({name: 'containers.index'});
                console.log("WHY");
            },
        }

    }
</script>
