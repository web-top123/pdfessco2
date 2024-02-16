<template>
    <modal @close="$emit('close')" class="document-created-confirmation">

        <template slot="header">
            <p slot="header" class="modal-card-title">Document Created</p>
        </template>

        <template>
            <a target="_blank" :href="filePath" class="document-info-body">
                Pdfglue-Document.pdf <span class="file-title"><a target="_blank" :href="filePath">View</a></span>
            </a>
            <iframe :src="filePath" id="pdf" name="pdf" style="display:none"></iframe>
            <div class="field is-grouped btwn">
                <a class="is-link send-email-link" :class="{'on-active': emailVisible}" @click="emailVisible =! emailVisible" ><i class="fa fa-envelope-o"></i>Send by Email<span class="border-send-email"></span></a>
                <a class="is-link send-printer-link" @click="sendToPrinter"><i class="fa fa-print"></i>Send to Printer</a>
            </div>
            <div class="send-by-email" v-if="emailVisible">
                <div class="field send-email-field">
                    <label class="label is-small" for="email-input"> SUBJECT </label>
                    <div class="control">
                        <input v-model="emailSubject" class="input is-medium send-by-email-input">
                    </div>
                </div>
                <div class="field send-email-field">
                    <label class="label is-small" for="email-input"> SEND EMAIL TO </label>
                    <div class="control">
                        <textarea type="email"
                            id="email-input"
                            ref="txa"
                            class="input is-medium send-by-email-input"
                            :class="{'is-danger' : errors.emailList.length > 0, 'is-success': successState === true, 'bounce-enter-active': failState === true}"
                            v-model="emailString"
                            placeholder="john@domain.com; chris@domain.com"
                            @keyup="checkEmail"></textarea>
                        <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.emailList.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.emailList}}
                        </p>
                    </div>
                </div>

                <button class="button is-info send-by-email-button" :class="{'is-success':successState===true }">
                    <transition name="fade">
                        <span class="save-button" v-if="sending === false" key="editing" @click="sendByEmail">Send Email</span>
                        <span class="save-button" v-if="sending === true" key="saved">Sending Email</span>
                    </transition>
                </button>
                <div class="email-sent-success" :class="{'bounce-enter-active':emailSuccess === true}" v-if="emailSuccess"> Your email has been successfully sent! <span class="fa fa-times-circle clear-confirmation" @click="emailSuccess = false;"></span></div>

            </div>

        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <a class="button is-info dl-file" :href="filePath" download="Pdfglue-Document.pdf">Download</a>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    data() {
        return  {
            filePath: this.$store.state.dashboard.filePath,
            emailVisible: false,
            errors:{
                emailList: ''
            },
            emailString: '',
            emailSubject: '',
            emailList: [],
            emailSuccess: false,
            sending: false

        };
    },
    computed:{
        successState(){
            return this.$store.state.dashboard.successState;
        },
        failState(){
            return this.$store.state.dashboard.failState;
        },
    },
    components: {
        Modal,
    },
    methods: {
        checkEmail(){
            this.$refs.txa.style.height = 'auto';
            this.$refs.txa.style.height = (this.$refs.txa.scrollHeight + 8) + 'px';
            this.errors.emailList = '';
        },
        sendByEmail(){

            if(!this.emailString.length){
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                this.errors.emailList = "You should include at least one email address.";
                setTimeout(this.resetStates, 150);

            }else{
                this.emailList = this.emailString.replace(/\s/g,'').split(';').filter(i=>i.length);
                this.sending = true;
                axios.post('/dashboard/email', {url: this.filePath, to: this.emailList, subject: this.emailSubject})
                .then(({data}) => {
                    this.sending = false;
                    if(data.success){
                        this.$store.commit('dashboard/failStateMutation', false);
                        this.$store.commit('dashboard/successStateMutation', true);
                        this.errors.emailList = '';
                        this.emailString = '';
                        this.emailSuccess = true;
                        setTimeout(this.resetStates, 150);
                    }
                }).catch(({response}) => {
                    this.sending = false;
                    this.$store.commit('dashboard/successStateMutation', false);
                    this.$store.commit('dashboard/failStateMutation', true);
                    this.errors.emailList = response.data.message;
                    setTimeout(this.resetStates, 150);
                });
            }
        },
        sendToPrinter(){
            this.emailVisible = false;
            window.frames['pdf'].print();
        },
        viewDocument(){
            var win = window.open(this.filePath, '_blank');
            win.focus();
        },
        resetStates(){
            this.$store.dispatch('dashboard/resetStatesAction')
        }
    },
}

</script>

<style lang="scss">
@import "resources/assets/sass/variables";
.modal.document-created-confirmation{
    .fade-enter-active{
    transition: opacity .5s
    }
    .fade-leave-active{
        display: none;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0
    }
    .email-sent-success{
        border: 1px solid #7acca3;
        color: #1bb267;
        text-align: center;
        margin-top: 24px;
        padding: 9px;
        border-radius: 8px;
        box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.1);
        span{
            color: $color-text-light;
            cursor: pointer;
            margin-left: 5px;
        }
    }
    .send-by-email{
        border: 1px solid #e1e2e5;
        border-radius: 0 8px 8px 8px;
        background: #f7f7f7;
        padding: 23px 23px 35px;
        margin-top: -1px;
        position: relative;
        z-index: 1;
        .send-by-email-button, .send-by-email-input{
            width: 100%;
        }
        textarea.send-by-email-input{
            padding-top: 14px;
            overflow: hidden;
            font-size: 14px;
        }
        .send-by-email-button{
            height: 40px;
            border-radius: 8px;
            color: #fff;
            padding: 0;
            text-align: center;
            font-weight: 700;
            transition: all .3s;
            background-color: $color-primary;
            -webkit-box-shadow: 0 3px 7.84px 0.16px rgba(0, 0, 0, 0.15);
            box-shadow: 0 3px 7.84px 0.16px rgba(0, 0, 0, 0.15);
            &:hover{
                background-color: $color-dark;
                -webkit-box-shadow: 0 4px 7.84px 0.16px rgba(0, 0, 0, 0.2);
                box-shadow: 0 4px 7.84px 0.16px rgba(0, 0, 0, 0.2);
            }
            .save-button{
                font-size: 14px;
                line-height: 40px;
                position: absolute;
                left: 0;
                right: 0;
            }
        }
    }
    .field:not(:last-child).send-email-field{
        margin-bottom: 24px;
    }
    .field:not(:last-child).is-grouped.btwn{
        margin-bottom: 0;
    }
    .modal-card-body .field.is-grouped .is-link{
        border-bottom: 1px solid #f7f7f7;
        padding: 5px;
    }
    .field.is-grouped.btwn{
        margin-bottom: 0;
        position: relative;
        z-index: 2;
        justify-content: space-between;
        .is-link{
            border-radius: 8px 8px 0 0;
            color: $color-primary;
            padding: 5px;
            // transition: border 0.3s;
            font-size: 16px;
            @media (max-width: 550px){
                font-size: 0;
            }
            position: relative;

            margin:0;
            width: 50%;
            text-align: center;
            padding: 10px;
            border-top: 1px solid transparent;
            border-right: 1px solid transparent;
            border-left: 1px solid transparent;
            border-bottom: 1px solid transparent;
            &.send-email-link, &.send-printer-link{
                padding: 14px;
            }
            &.send-email-link{
                .border-send-email{
                    width: 134px;
                    height: 2px;
                    background-color: #fff;
                    position: absolute;
                    margin: 0 auto;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }
            }
            .fa{
                font-size: 21px;
                margin-right: 7px;
            }
            &:hover{
                // border-bottom: 2px solid $color-primary;
            }
            &.on-active{
                border-top: 1px solid #e1e2e5;
                border-right: 1px solid #e1e2e5;
                border-left: 1px solid #e1e2e5;
                border-bottom: 1px solid #f7f7f7;
                background: #f7f7f7;
                &.send-email-link:after {
                    content: "";
                    position: absolute;
                    height: 1px;
                    width: 1px;
                    background: #e1e2e5;
                    bottom: -1px;
                    right: -1px;
                }
                &.send-email-link:before {
                    content: "";
                    position: absolute;
                    height: 1px;
                    width: 1px;
                    background: #e1e2e5;
                    bottom: -1px;
                    left: -1px;
                }
            }
        }
    }
    .modal-card{
        width: 520px;
        max-width: 100%;
        overflow: auto;
        .modal-card-head{
            padding: 65px 80px 60px;
        }
    }
    .document-info-body{
        align-items: center;
        border: 2px solid #d2d4d6;
        border-radius: 8px;
        cursor: pointer;
        color: $color-text-primary;
        display: flex;
        justify-content: space-between;
        padding: 1em 1.25em;
        margin-bottom: 35px;
        @media (max-width: 550px){
            margin-bottom: 15px;
        }
        transition: border 0.3s;
        &:hover{
            border: 2px solid $color-primary;
            .file-title a{
                color: $color-primary;
            }
        }
        .file-title{
            margin-left: 30px;
            a{
                color: #808080;
                font-size: 14px;
                transition: color 0.3s;
            }
        }
    }
    .modal-card-foot{
        a.button.is-info.dl-file{
            border-color: $color-primary
        }
    }

}
</style>
