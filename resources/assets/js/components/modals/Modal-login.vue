<template>
    <modal @close="$emit('close')" class="my-account login-modal">

        <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Log In</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small">EMAIL</label>
                <div class="control">
                    <input v-model="data.email" class="input is-medium" :class="{'is-danger' : (errors.email.length>0)}" type="email" placeholder="example@domain.com" @keyup="errors.email = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.email.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{errors.email}}</p>
            </div>
            <div class="field">
                <label class="label is-small">PASSWORD</label>
                <div class="control">
                    <input v-model="data.password" class="input is-medium" type="password" placeholder="Must have at least 6 characters" @keyup="errors.password = '';errors.email = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.password.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{errors.password}}</p>
            </div>
            <div class="field is-grouped remember-me">
                <span>
                    <input type="checkbox" v-model="data.remember" class="styled-checkbox" id="remember_me" ref="rmbMe"></input>
                    <label for="remember_me" @mouseenter="hoverRmb = true" @mouseleave="hoverRmb = false"></label>
                    <span class="rmb" :class="{'active' : hoverRmb}" @click="$refs.rmbMe.click()">Remember me</span>
                </span>
                <a class="" @click="$emit('password')">Forgot Password?</a>
            </div>

        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="login">Log In</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    props: {
        user: {
            type: Object,
        }
    },
    data() {
        return {
            data: undefined,
            errors: {
                email: '',
                password:'',
            },
            hoverRmb: false
        }
    },
    created() {
            this.data = Object.assign({},this.user);
    },
    components: {
        Modal,
    },
    methods: {
        login() {

            axios.post('/login', {email: this.data.email, password: this.data.password, remember: this.data.remember})
                .then(({data}) => {
                    if (data.url) {
                        window.location.href=data.url;

                    } else {

                        window.location.href= '/login';
                    }
                }).catch(({response}) => {
                    if(response.data.errors.email) {
                        this.errors.email = response.data.errors.email[0];
                    }
                    if(response.data.errors.password) {
                        this.errors.password = response.data.errors.password[0];
                    }
                });
        },
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
.modal {
    &.login-modal{
        .modal-card-body {
            .field.is-grouped.remember-me{
                margin-top: 25px;
                justify-content: space-between;
                .styled-checkbox {
                    &+label{
                        width: 25px;
                        &:before{
                            height: 16px;
                            width: 16px;
                        }
                        &:after{
                            // top: 9px;
                        }
                    }
                }
                .rmb, a{
                    color: $color-primary;
                    padding: 0;
                    font-weight: 500;
                    cursor: pointer;
                    border-bottom: 2px solid transparent;
                    &:hover, &.active{
                        color: $color-dark;
                        border-bottom: 2px solid $color-dark;
                    }
                }

            }
        }
        .modal-card-foot{
            padding: 22px 80px 60px;
        }
    }
}
</style>
