
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
// import PerfectScrollbar from 'vue2-vue-perfect-scrollbar'

// import SystemJS from "systemjs";
Vue.use(Vuex);
Vue.use(VueRouter); 
// Vue.use(PerfectScrollbar)
/**
 * Runtime components
 */
import Navbar from './components/Navbar.vue';
import Timeout from './timeout';
/**s
 * Async components
 */
// import Dashboard from './components/Dashboard';
 const Dashboard = () => import('./components/Dashboard');
/**
 * Store configuration
 */




window.Store = new Vuex.Store({
    state: {
        user: undefined,
    },
    mutations: {
        user: (state, value) => state.user = value,
    },
});
window.Store.commit('user', __Data.user);






// /**
//  * Routes configuration
//  */
const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: Dashboard,
        },
        {
            path: '*',
            redirect: '/',
        },
    ]
});

// /**
//  * Application configuration
//  */

const app = new Vue({
    el: '#app',
    store: window.Store,
    router,
    components: {
        Navbar,
    },
    mounted() {
        Timeout(this.$store.state.user.timeouts.user ? this.$store.state.user.timeouts.user : this.$store.state.user.timeouts.global);
    }
});
