<template>
    <modal @close="$emit('close')" class="change-pass-container">

        <template slot="header">
            <p slot="header" class="modal-card-title">Change Password</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small">NEW PASSWORD</label>
                <div class="control">
                    <input v-model="password" class="input is-medium" type="password" @keyup="errors.password = ''">
                </div>
                <p class="help is-danger" :class="{'is-hidden' : !errors.password}">{{errors.password}}</p>
            </div>
            <div class="field">
                <label class="label is-small">CONFIRM NEW PASSWORD</label>
                <div class="control">
                    <input v-model="passwordConfirmed" class="input is-medium" type="password" @keyup="errors.password = ''">
                </div>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="reset">Reset Password</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    data(){
        return {
            errors: {
                cur: '',
                password: '',
            },
            cur: '',
            password: '',
            passwordConfirmed: '',
        }
    },
    components: {
        Modal,
    },
    methods: {
        reset(){

            axios.patch('/update/password', {password_confirmation: this.passwordConfirmed, password: this.password, cur: this.cur})
            .then(() => { this.$emit('close')})
            .catch(({response}) => {
                if(response.data.errors.password) {
                    this.errors.password = response.data.errors.password[0];
                }

                if(response.data.errors.cur) {
                    this.errors.cur = response.data.errors.cur[0];
                }
            });
        }
    }

}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.change-pass-container{

    input {
        @include box-sizing(border-box);
        height: 50px!important;
    }
    .modal-card{
        width: auto;
        max-width: 510px;
    }
}

</style>
