<template>
    <modal @close="$emit('close')" class="my-account">

        <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">My Account</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small">NAME</label>
                <div class="control">
                    <input v-model="data.name" class="input is-medium" :class="{'is-danger' : errors.name.length, 'is-success': successState === true, 'bounce-enter-active': failState === true}"  type="text" placeholder="Your name..." @keyup="errors.name = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'is-hidden' : !errors.name.length,'bounce-enter-active':failState === true}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.name}}</p>
            </div>
            <div class="field">
                <label class="label is-small">EMAIL</label>
                <div class="control">
                    <input v-model="data.email" class="input is-medium"  :class="{'is-danger' : errors.email.length, 'is-success': successState === true, 'bounce-enter-active': failState === true}" type="email" placeholder="Your email..." @keyup="errors.email = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'is-hidden' : !errors.email.length,'bounce-enter-active':this.failState === true}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.email}}</p>
            </div>
            <div class="control change-pass">
                <button class="button" @click="$emit('password')">Change Password</button>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" :class="{'is-success':successState===true }" >
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="update">Save</span> 
                    <span class="ok-button"  v-if="successState === true" key="saved"><i class="fa fa-check-circle" aria-hidden="true"></i></span> 
                </transition>
            </button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {

    data() {
        return {            
            user: this.$store.state.user,
            data: undefined,
            errors: {
                name: '',
                email: '',
            },
            successState: false,
            failState: false
        }
    },
    created() {
            this.data = Object.assign({},this.user);
    },
    components: {
        Modal,
    },
    methods: {
        update() {
            axios.patch('/update', {name: this.data.name, email: this.data.email})
                .then(({data}) => {
                    this.$store.commit('user', data);
                    this.data = Object.assign({}, data);
                    this.successState = true;
                    var that = this;
                    setTimeout(function(){that.$emit('close')}, 200);
                    setTimeout(this.resetFields, 201);
                })
                .catch(({response}) => {
                    this.successState = false;
                    this.failState = true;
                    setTimeout(()=>{this.failState=false; this.successState=false}, 150);
                    if(response.data.errors.email) {
                        this.errors.email = response.data.errors.email[0];
                    }
                    if(response.data.errors.name) {
                        this.errors.name = response.data.errors.name[0];
                    }
                });
        },
        resetFields(){
            this.failState=false; 
            this.successState=false;
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.my-account{
    z-index: 99999999;
    input {
        @include box-sizing(border-box);
        height: 50px!important;
    }
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
        display: inline-flex;
        padding: 0;
        text-decoration: none;
        transition: all 0.3s;
        height: auto;
        &:hover, &:active, &:focus {
            background: #fff;
            box-shadow: none;
            border-bottom: 2px solid $color-primary;
        }
    }
}

</style>
