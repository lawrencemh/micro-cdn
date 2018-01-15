
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Load vue-router
Vue.use(VueRouter);

// Setup the router
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'Account',
            path: '/account',
            component: require('./components/Containers.vue')
        },
    ]
});

const Layout = require('./components/Layout.vue');

const app = new Vue({
        el: '#app',
        router,
        render: r => r(Layout)
});
