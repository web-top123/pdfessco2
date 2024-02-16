<template>
    <modal :show="show" @close="$emit('close')" class="my-account">

        <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Create {{admin ? 'Admin' : 'User'}}</p>
        </template>


        <template>
            <div class="field">
                <label class="label is-small">FULL NAME</label>
                <div class="control">
                    <input v-model="data.name" class="input is-medium" :class="{'is-danger' : (errors.name.length>0)}" type="text" placeholder="Fullname..." @keyup="errors.name = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.name.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.name}}</p>
            </div>
            <div class="field">
                <label class="label is-small">EMAIL</label>
                <div class="control">
                    <input v-model="data.email" class="input is-medium" :class="{'is-danger' : (errors.email.length>0)}" type="email" placeholder="Email..." @keyup="errors.email = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.email.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.email}}</p>
            </div>
            <div class="field">
                <label class="label is-small">PASSWORD</label>
                <div class="control">
                    <input v-model="data.password" class="input is-medium" :class="{'is-danger' : (errors.password.length>0)}" type="password" placeholder="Must have at least 6 characters" @keyup="errors.password = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.password.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.password}}</p>
            </div>
            <div class="field">
                <label class="label is-small">CONFIRM PASSWORD</label>
                <div class="control">
                    <input v-model="data.password_confirmation" class="input is-medium" type="password" placeholder="Must have at least 6 characters" @keyup="errors.password_confirmation = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.password_confirmation.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.password_confirmation}}</p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="save">Save</button>
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
        admin: {
            type: Boolean,
            default: false,
        },
        user:{
            type: Object
        }
    },
    data() {
        return {
            data: undefined,
            name : '',
            email: '',
            password: '',
            confirm: '',
            errors:{
                name:'',
                email:'',
                password:'',
                password_confirmation:''
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
        save() {
            axios.post('/admin/user' + (this.admin ? '/admin' : ''), {name: this.data.name, email: this.data.email, password: this.data.password, password_confirmation: this.data.password_confirmation}).then(({data}) => {
                $('#table').DataTable().draw();
                this.$emit('close');
            }).catch(({response}) => {
                if(response.data.errors && response.data.errors.name) {
                    this.errors.name = response.data.errors.name[0];
                }
                if(response.data.errors && response.data.errors.email) {
                    this.errors.email = response.data.errors.email[0];
                }
                if(response.data.errors && response.data.errors.password) {
                    if(response.data.errors.password.length>1){
                        for(var thing in response.data.errors.password){
                            if(response.data.errors.password[thing].indexOf('confirm') !== -1){
                                this.errors.password_confirmation = response.data.errors.password[thing];
                            }else{
                                this.errors.password = response.data.errors.password[thing];
                            }
                        }
                    }else{
                        if(response.data.errors.password[0].indexOf('confirm') !== -1){
                            this.errors.password_confirmation = response.data.errors.password[0];
                        }else{
                            this.errors.password = response.data.errors.password[0];
                        }
                    }
                }
            });
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.my-account {
    .modal-card {
        width: auto;
        overflow: visible;

        .modal-card-body {
            .field:not(:last-child){
                margin-bottom: 25px!important;

            }
            input {
                @include box-sizing(border-box);
                height: 50px!important;
                // margin-bottom: -5px!important;
            }
        }
        .modal-card-foot {

            // button {
            //     height: 50px!important;
            // }

        }
    }

    .form-group {
        font-family: $font-family;
        margin-bottom: 30px;
        @include flex;
        @include flex-direction-column;
        &:last-child {
            margin-bottom: 0;
        }
        label {
            padding-left: 15px;
            display: block;
            color: $color-text-tertiary;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 10px;
        }

        input,
        textarea {
            font-family: $font-family;
            display: block;
            @include fine-border(#cccccc);
            border-width: 2px;
            border-radius: 10px;
            padding: 15px 18px 10px;
            color: $color-text-primary;
            // width: 100%;
            font-weight: 400;
            font-size: 16px;
            // width:100%;
            // min-width: 350px;

            &:focus,
            &:hover {
                @include fine-border($color-tertiary);
                border-width: 2px;
            }
        }

        input {
            @include box-sizing(border-box);
            height: 50px;
        }

        textarea {
            resize: none;
            overflow: hidden;
            font-size: 14px;
        }

        input,
        textarea {
            &.drag,
            &:focus {
                @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
            }
        }
    }

}
</style>
