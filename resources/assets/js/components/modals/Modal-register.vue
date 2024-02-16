<template>
    <modal :show="show" @close="$emit('close')" class="my-account">

        <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Register</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small">FULL NAME</label>
                <div class="control">
                    <input v-model="data.name" class="input is-medium" :class="{'is-danger' : (errors.name.length>0)}" type="text" placeholder="First name Last name" @keyup="errors.name = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.name.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.name}}</p>
            </div>
            <div class="field">
                <label class="label is-small">EMAIL</label>
                <div class="control">
                    <input v-model="data.email" class="input is-medium" :class="{'is-danger' : (errors.email.length>0)}" type="email" placeholder="example@domain.com" @keyup="errors.email = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.email.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.email}}</p>
            </div>
            <div class="field">
                <label class="label is-small">PASSWORD</label>
                <div class="control">
                    <input v-model="data.password" class="input is-medium" :class="{'is-danger' : (errors.password.length>0)}" type="password" placeholder="Must have at least 6 characters" @keyup="errors.password = ''">
                </div>
                <p class="help is-danger" v-for="(err, index) in errors.password" :key="index">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{err}}
                    </p>
            </div>
            <div class="field">
                <label class="label is-small">CONFIRM PASSWORD</label>
                <div class="control">
                    <input v-model="data.password_confirmation" class="input is-medium" type="password" placeholder="Must have at least 6 characters" @keyup="errors.password_confirmation = ''">
                </div>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="register">Register</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    props: {
        show: {
            type: Boolean,
            required: true,
            default: false,
        },
        user: {
            type: Object,
        }
    },
    data() {
        return {
            data: undefined,
            errors: {
                email: '',
                password: [],
                password_confirmation: [],
                name: ''
            }
        }
    },
    created() {
            this.data = Object.assign({},this.user);
    },
    components: {
        Modal,
    },
    methods: {
        register() {
            axios.post('/register', {name:this.data.name, email: this.data.email, password: this.data.password, password_confirmation: this.data.password_confirmation})
            .then(({data}) => {
                window.location.href=data.url;
            })
            .catch(({response}) => {
                if(response.data.errors && response.data.errors.name) {
                    this.errors.name = response.data.errors.name[0];
                }
                if(response.data.errors && response.data.errors.email) {
                    this.errors.email = response.data.errors.email[0];
                }
                if(response.data.errors && response.data.errors.password) {
                    this.errors.password = response.data.errors.password;
                }
            });
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
.my-account{
    .modal-card{
        width: auto;
        min-width: 520px;
        max-width: 760px;
    }
    .control.change-pass{
        text-align: center;
        margin-top: 35px;
    }
    .control.change-pass .button{
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        color: $color-primary;
        padding: 0;
        height: auto;
        text-decoration: none;
        transition: all 0.3s;
        &:hover, &:active, &:focus {
            background: #fff;
            box-shadow: none;
            border-bottom: 2px solid $color-primary;
        }
    }
}

</style>
