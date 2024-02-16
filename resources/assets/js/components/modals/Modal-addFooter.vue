<template>
    <modal @close="$emit('close')" class="add-footer-modal">

        <template slot="header">
            <p slot="header" class="modal-card-title">{{footerState}} Footer</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small" for="footer-content"> FOOTER CONTENT <span class="char-counter"><span class="current-char" :class="{'is-danger':footerContent.length>maxChar}">{{footerContent.length}}</span> / <span class="max-char"></span>{{maxChar}}</span></label>
                <div class="control">
                    <input type="text"
                           id="footer-content"
                           class="input is-medium"
                           :class="{'is-danger' : errors.footer.length > 0, 'is-success': successState === true, 'bounce-enter-active': failState === true}"
                           v-model="footerContent"
                           placeholder="Type in content for the footer"
                           @keyup="checkInput"></input>
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.footer.length > 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.footer}}
                        <span v-if="footerContent.length>maxChar">({{footerContent.length-maxChar}} too many).</span>
                    </p>
                </div>
            </div>

        </template>

        <template slot="footer">
            <a class="is-delete" :class="{'disabled-delete':footerState=='Add'}" ><span class="is-delete-link" @click="$emit('delete')">Delete Footer</span></a>
            <button class="button" @click="cancelFooter">Cancel</button>
            <button class="button is-info" :class="{'is-success':successState===true }">
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="saveFooter">Save</span>
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
            maxChar: 60,
            errors:{
                footer: ""
            },
            footerContent: this.$store.state.dashboard.modals.addFooter.content,
            hoverRmb: false
        };
    },
    components: {
        Modal,
    },
    computed:{
        successState(){
            return this.$store.state.dashboard.successState;
        },
        failState(){
            return this.$store.state.dashboard.failState;
        },
        footerExists(){
            return this.$store.state.dashboard.modals.addFooter.exists;
        },
        footerState(){
            return !this.$store.state.dashboard.modals.addFooter.exists ? 'Add' : 'Edit';
        },
        removeNumbering:{
            get(){
                return !this.$store.state.dashboard.removeNumbering;
            },
            set(value){
                this.$store.state.dashboard.removeNumbering = !value;
            }
        }

    },
    methods: {
        cancelFooter(){
            // console.log(this.$store.state.dashboard.modals.addFooter.content)
            this.footerContent = this.$store.state.dashboard.modals.addFooter.content;
            this.$emit('close');
        },
        checkInput(){
            this.errors.footer = '';
            if(this.footerContent.length > this.maxChar){
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                this.errors.footer = "Maximum " + this.maxChar + " characters";
                setTimeout(this.resetStates, 150);
            }else{
                this.$store.commit('dashboard/failStateMutation', false);
            }
        },
        saveFooter(){
            // if(!this.footerContent.length){
            //     this.$store.commit('dashboard/successStateMutation', false);
            //     this.$store.commit('dashboard/failStateMutation', true);
            //     this.errors.footer = 'You cannot submit an empty footer';
            //     setTimeout(this.resetStates, 150);
            // }else{
                if(this.footerContent.length > this.maxChar){
                    this.$store.commit('dashboard/successStateMutation', false);
                    this.$store.commit('dashboard/failStateMutation', true);
                    this.errors.footer = "Maximum " + this.maxChar + " characters";
                    setTimeout(this.resetStates, 150);
                }else{
                    this.$store.commit('dashboard/successStateMutation', true);
                    setTimeout(this.submitSave, 300);
                }
            // }
        },
        submitSave(){
            this.$store.commit('dashboard/saveHFC', {name:'addFooter',data:this.footerContent})
            // this.$emit('save')
        },
        resetStates(){
            this.$store.dispatch('dashboard/resetStatesAction')
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
.field.is-grouped.remember-me{
    margin-top: 25px;
    justify-content: space-between;
    .styled-checkbox {
        &+label{
            width: 25px;
            &:before{
                height: 16px;
                width: 16px;
                margin-top: 0;
            }
            &:after{
                top: 9px;
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

</style>
