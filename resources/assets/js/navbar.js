module.exports = () => {

    return {
        components: {
            ModalAccount: require('./components/modals/Modal-myAccount.vue'),
            ModalPassword: require('./components/modals/Modal-changePass.vue'),
            ModalLogin: require('./components/modals/Modal-login.vue'),
            ModalRegister: require('./components/modals/Modal-register.vue'),
        },
        data: {
            navbar: {
                dropdown: false,
                account: false,
                password: false,
                loginModal: false,
                registerModal: false,
                user: user || '',
                errors: {email:''}
            }
        },
        methods: {
            navbarOutside(ev) {
                if (ev.target.id !== 'navbar-dropdown' && ev.target.parentNode.id !== 'navbar-dropdown') {
                    this.navbar.dropdown = false;
                }
            },
            logout() {

                axios.post('/logout').then(() => window.location.href = "/");
            },
            updateUser(user) {
                this.navbar.user = user;
                this.navbar.dropdown = false;
                this.navbar.account = false;
            },
            //obsolete
            login(data){
                axios.post('/login', {email: data.email, password: data.password})
                .then(({data}) => {
                    window.location.href=data.url;
                }).catch(({response}) => {
                    if(response.data.errors.email) {
                        this.navbar.errors.email = response.data.errors.email[0];
                    }
                });
            },
            register(data){
                axios.post('/register', {name:data.name, email: data.email, password: data.password, password_confirmation: data.password})
                .then(({data}) => {
                    window.location.href=data.url;
                })
                .catch(({response}) => {
                    if(response.data.errors && response.data.errors.email) {
                        this.navbar.errors.email = response.data.errors.email[0];
                    }
                });
            }
        },
    }


}
