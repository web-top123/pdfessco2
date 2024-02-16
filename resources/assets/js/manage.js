
/**
 * Initial config from the bootstrap file
 */
import './bootstrap';

/**
 * Vendor libraries
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Timeout from './timeout';
// import {VueMasonryPlugin} from 'vue-masonry';

Vue.use(Vuex);
Vue.use(VueRouter);
// Vue.use(VueMasonryPlugin);

/**
 * Runtime components
 */
import Navbar from './components/Navbar.vue';

/**
 * Async components
 */

const ManageFiles = () => import('./components/Manage-files');
const ManageUsers = () => import('./components/Manage-users');
const Dashboard = () => import('./components/Dashboard');
const ManageTimeouts = () => import('./components/Manage-timeouts');


// import ManageFiles from './components/Manage-files';
// import ManageUsers from './components/Manage-users';
// import Dashboard from './components/Dashboard';
// import ManageTimeouts from './components/Manage-timeouts';

/**
 * Store configuration
 */
window.Store = new Vuex.Store({
    state: {
        user: undefined,
    },
    mutations: {
        user: (state, value) => state.user = value,
        setGlobalTimeout: (state, value) => state.user.timeouts.global = parseInt(value),
    },
    getters: {
    }
});



window.Store.commit('user', __Data.user);

/**
 * Routes configuration
 */
const router = new VueRouter({

    routes: [{
            path: '/manage-files',
            component: ManageFiles,
        },

        {
            path: '/manage-users',
            component: ManageUsers,
        },

        {
            path: '/dashboard',
            component: Dashboard,
        },

        {
            path: '*',
            redirect: '/manage-files',
        },

        {
            path: '/timeout',
            component: ManageTimeouts,
        }
    ]
});

/**
 * Application configuration
 */
const app = new Vue({
    el: '#app',
    store: window.Store,
    router,
    components: {
        Navbar,
    },
    mounted(){
        Timeout(this.$store.state.user.timeouts.user ? this.$store.state.user.timeouts.user : this.$store.state.user.timeouts.global);
    },
});
