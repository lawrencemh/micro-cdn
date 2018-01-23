
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

// Load vue-cookie
Vue.use(VueCookie);

// Initiate the ApiRestResource service we will use to interact with the API
import ApiRestResource from './services/ApiRestResource';
window.apiRestResourceService = new ApiRestResource();

// Setup the router
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'account.auth.login',
            path: '/account/auth/login',
            component: require('./components/pages/auth/Login.vue')
        },
        {
            name: 'containers.index',
            path: '/account/containers/',
            component: require('./components/pages/containers/Index.vue')
        },
        {
            name: 'containers.create',
            path: '/account/containers/create',
            component: require('./components/pages/containers/Create.vue')
        },
        {
            name: 'containers.update',
            path: '/account/containers/:id/update',
            component: require('./components/pages/containers/Update.vue')
        },
        {
            name: 'containers.media.index',
            path: '/account/containers/:id/media/',
            component: require('./components/pages/media/Index.vue')
        },
        {
            name: 'containers.media.create',
            path: '/account/containers/:id/media/create',
            component: require('./components/pages/media/Create.vue')
        },
        {
            name: 'containers.media.update',
            path: '/account/containers/:containerId/media/:mediaId/edit',
            component: require('./components/pages/media/Update.vue')
        },
    ]
});

const Layout = require('./components/Layout.vue');

const app = new Vue({
        el: '#app',
        router,
        render: r => r(Layout)
});
