<template>
    <modal @close="$emit('close')" class="change-pass-container forgot-pass-container">

        <template slot="header">
            <p slot="header" class="modal-card-title">Forgot Password</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small">EMAIL</label>
                <div class="control">
                    <input v-model="email" class="input is-medium" :class="{'is-danger' : errors.email.length !== 0, 'is-success': successState === true, 'bounce-enter-active': failState === true}"  placeholder="Your account's email address" type="email" @keyup="errors.email = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.email.length !== 0">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.email}}
                </p>
            </div>
            
        </template>
        <template slot="footer">
            <div class="footer-buttons">
                <button class="button cancel-forgot" @click="$emit('success')">Cancel</button>
                <button class="button is-info" @click="reset">Send Reset Email</button>
            </div>
            <div class="forgot-notification">We'll send you a link to reset your Pdfglue password!</div>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    data(){
        return {
            errors: {
                email: ''
            },
            email: '',
            failState: false,
            successState: false,
        }
    },
    components: {
        Modal,
    },
    methods: {
        reset(){
            if(!this.email){
                this.successState = false;
                this.failState = true;
                this.errors.email = 'Please fill in the email field';
                setTimeout(this.resetStates, 150);
            }else{
                var headers = {
                    "accept": "application/json",
                }
                axios.post('/password/email',  {email: this.email}, headers)
                    .then((res) => {this.$emit('close');this.$emit('success');})
                    .catch(({response}) => {
                        if(response.data.errors && response.data.errors.email[0].length){
                            this.successState = false;
                            this.failState = true;
                            this.errors.email = response.data.errors.email[0];
                            setTimeout(this.resetStates, 150);
                        }
                    });
            }
        },
        resetStates(){
            this.failState = false;
            this.successState = false;
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
.modal.forgot-pass-container .modal-card-foot{
    display: block;
    .footer-buttons{
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        .cancel-forgot{
            max-width: 124px;
        }
        .button.is-info{
            max-width: 210px;
            flex-grow: 1;
        }
    }
    .forgot-notification{
        border-top: 1px solid #ccc;
        color: $color-text-tertiary;
        font-family: Raleway;
        font-size: 14px;
        line-height: 1;
        padding-top: 15px;
    }
}

</style>
