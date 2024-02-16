window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

/**
 * Vendor libraries
 */
import Vue from 'vue';
import Vuex from 'vuex';
import Timeout from './timeout';

Vue.use(Vuex)

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
    getters: {
    }
});


if (window.__Data) {
    window.Store.commit('user', window.__Data.user);
}

const app = new Vue({
    el: '#app',
    store: window.Store,
    components: {
        ModalAccount: require('./components/modals/Modal-myAccount.vue'),
        ModalPassword: require('./components/modals/Modal-changePass.vue'),
    },
    data: {
        login:{
            show: false,
            errors:{
                email: '',
                password: ''
            },
            email:'',
            password:'',
            remember: false,
            successState: false,
            failState: false
        },
        forgotReset:{
            show: false,
            errors:{
                email: '',
                password: '',
                password_confirmation: '',
            },
            email: '',
            password: '',
            password_confirmation: '',
            successState: false,
            failState: false
        },
        forgotPass: {
            show: false,
            errors:{
                email: '',
                password: '',
                password_confirmation: '',
            },
            email: '',
            password: '',
            password_confirmation: '',
            successState: false,
            failState: false
        },
        forgotConfirm: false,
        successState: false,
        failState: false,
        dropdown: false,
        dropdownMenu: false,
        navbar: {
            account: false,
            password: false,
            loginModal: false,
            registerModal: false,
            errors: {email:''}
        },
        startedEmail: '',

    },
    mounted() {
        document.getElementById('app').className = "presentation-app";
        document.getElementById('footer').className += " active";
        if (getParameterByName('email')) {
            this.login.email = getParameterByName('email');
        }

        if (this.$store.state.user) {
            Timeout(this.$store.state.user.timeouts.user ? this.$store.state.user.timeouts.user : this.$store.state.user.timeouts.global);
        }
    },
    computed: {
        user() {
            return this.$store.state.user
        },
        userName() {
            return this.$store.state.user.name;
        }
    },
    methods:{
        dropMenu() {
            this.dropdownMenu = !this.dropdownMenu;
        },
        navigate(path) {
            window.location.href = path;
        },
        navbarOutside(ev) {
            if (window.event.target.id !== 'user-dropdown' && window.event.target.parentNode.id !== 'user-dropdown') {
                this.toggleDropdown();
            }
        },
        toggleDropdown() {
            if (this.dropdown) {
                window.removeEventListener('click', this.navbarOutside);
                this.dropdown = false;
            } else {
                window.addEventListener('click', this.navbarOutside);
                this.dropdown = true;
            }
        },
        logout() {
            axios.post('/logout').then( () => window.location.href="/");
        },
        attemptLogin(){
            if(!this.login.email.length || !this.login.password.length){
                this.failState = true;
                if(!this.login.email.length){
                    this.login.errors.email = "The email field is required";
                }
                if(!this.login.password.length){
                    this.login.errors.password = "The password field is required";
                }
                setTimeout(this.resetFields, 150);
            }else{
                axios.post('/login', {email: this.login.email, password: this.login.password, remember: this.login.remember})
                .then(({data}) => {
                    this.successState = true;
                    setTimeout(this.resetFields, 150);
                    if (data.url) {
                        window.location.href=data.url;
                    } else {
                        window.location.href= '/login';
                    }
                }).catch(({response}) => {
                    this.successState = false;
                    this.failState = true;
                    setTimeout(this.resetFields, 150);
                    if(response.data.errors.email) {
                        this.login.errors.email = response.data.errors.email[0];
                    }
                    if(response.data.errors.password) {
                        this.login.errors.password = response.data.errors.password[0];
                    }
                });
            }
        },
        attemptReset(){
            if(!this.forgotReset.email.length || !this.forgotReset.password.length){
                this.failState = true;
                if(!this.forgotReset.email.length){
                    this.forgotReset.errors.email = "The email field is required";
                }
                if(!this.forgotReset.password.length){
                    this.forgotReset.errors.password = "The password field is required";
                }
                setTimeout(this.resetFields, 150);
            }else{
                this.$refs.resetForm.submit();
            }

        },
        attemptForgot(){
            if(!this.forgotPass.email){
                this.successState = false;
                this.failState = true;
                this.forgotPass.errors.email = 'Please fill in the email field';
                setTimeout(this.resetFields, 150);
            }else{
                this.$refs.forgotForm.submit();

            }
        },
        resetFields(){
            this.successState = false;
            this.failState = false;
        },
        clearError(e) {
            if (e.target.parentNode.className.split(' ').indexOf('error') > -1) {
                e.target.parentNode.className = 'contact-input';
            }
        }
    }
});
