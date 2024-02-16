<template>
    <modal @close="$emit('close')" class="change-pass-container">

        <template slot="header">
            <p slot="header" class="modal-card-title">Change Password</p>
        </template>

        <template>
            <!-- <input type="text" style="position:absolute;left:200000px"> -->
            <div class="field">
                <label class="label is-small">CURRENT PASSWORD</label>
                <div class="control">
                    <input v-model="current_password" class="input is-medium" placeholder="Please type in your current password" name="current_password" :class="{'is-danger' : errors.current_password, 'is-success': successState === true, 'bounce-enter-active': this.failState === true}" type="password" @keyup="errors.current_password = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'is-hidden' : !errors.current_password.length,'bounce-enter-active':this.failState === true}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.current_password}}</p>
            </div>
            <div class="field">
                <label class="label is-small">NEW PASSWORD</label>
                <div class="control">
                    <input v-model="password" class="input is-medium" placeholder="Please type in your new password" name="new_password" :class="{'is-danger' : errors.password, 'is-success': successState === true, 'bounce-enter-active': this.failState === true}"  type="password" @keyup="errors.password = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'is-hidden' : !errors.password,'bounce-enter-active':this.failState === true}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.password}}</p>
            </div>
            <div class="field">
                <label class="label is-small">CONFIRM NEW PASSWORD</label>
                <div class="control">
                    <input v-model="password_confirmation" class="input is-medium" placeholder="Please type in your new password confirmation" name="password_confirmation" :class="{'is-danger' : errors.password_confirmation, 'is-success': successState === true, 'bounce-enter-active': this.failState === true}"  type="password" @keyup="errors.password_confirmation = ''">
                </div>
                <p class="help is-danger error-message-flash" :class="{'is-hidden' : !errors.password_confirmation,'bounce-enter-active':this.failState === true}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.password_confirmation}}</p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" :class="{'is-success':successState===true }" >
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="reset">Change Password</span> 
                    <span class="ok-button"  v-if="successState === true" key="saved"><i class="fa fa-check-circle" aria-hidden="true"></i></span> 
                </transition>
            </button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    data(){
        return {
            errors: {
                current_password: '',
                password: '',
                password_confirmation:''
            },
            current_password: '',
            password: '',
            password_confirmation:'',
            successState: false,
            failState: false
        }
    },
    components: {
        Modal,
    },
    methods: {
        reset(){
            axios.patch('/update/password', {password_confirmation: this.password_confirmation, password: this.password, cur: this.current_password})
            .then(() => { 
                this.successState = true;
                var that = this;
                setTimeout(function(){that.$emit('close')}, 200);
                setTimeout(this.resetFields, 201);
                
                })    
            .catch(({response}) => {
                this.successState = false;
                this.failState = true;
                setTimeout(()=>{this.failState=false; this.successState=false}, 150);
                if(response.data.errors.cur) {
                    this.errors.current_password = response.data.errors.cur[0];
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
        },
        resetFields(){
            this.failState=false; 
            this.successState=false;
            this.password = '';
            this.current_password = '';
            this.password_confirmation = '';

        }
    }

}
</script>

<style lang="scss">
.modal{
    &.change-pass-container{
        .modal-card{
            width: 520px;
            max-width: 760px;
        }
    }
}

</style>