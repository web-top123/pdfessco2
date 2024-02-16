<template>
    <modal @close="$emit('close')" class="add-header-modal">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">{{headerState}} Header</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small" for="footer-content"> HEADER CONTENT <span class="char-counter"><span class="current-char" :class="{'is-danger':headerContent.length>maxChar}">{{headerContent.length}}</span> / <span class="max-char">{{maxChar}}</span></span></label>
                <div class="control">
                    <input type="text" 
                            id="header-content" 
                            class="input is-medium" 
                            :class="{'is-danger' : errors.header.length !== 0, 'is-success': successState === true, 'bounce-enter-active': failState === true}" 
                            v-model="headerContent" 
                            placeholder="Type in content for the header"
                            @keyup="checkInput"></input>
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.header.length !== 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.header}} <span v-if="headerContent.length>maxChar">({{headerContent.length-maxChar}} too many).</span>
                    </p>
                </div>
            </div>
        </template>

        <template slot="footer">
            <a class="is-delete" :class="{'disabled-delete':!headerExists}"><span class="is-delete-link" @click="$emit('delete')">Delete Header</span></a>
            <button class="button" @click="cancelHeader">Cancel</button>
            <button class="button is-info" :class="{'is-success':successState===true }">
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="saveHeader">Save</span> 
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
                header:""
            },
            headerContent: this.$store.state.dashboard.modals.addHeader.content
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
        headerExists(){
            return this.$store.state.dashboard.modals.addHeader.exists;
        },
        headerState(){
            return !this.$store.state.dashboard.modals.addHeader.exists ? 'Add' : 'Edit';
        }

    },
    methods: {
        cancelHeader(){
            this.$emit('close');
        },
        checkInput(){
            this.errors.header = '';
            if(this.headerContent.length > this.maxChar){
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                this.errors.header = "Maximum " + this.maxChar + " characters";
                setTimeout(this.resetStates, 150);
            }else{
                this.$store.commit('dashboard/failStateMutation', false);
            }
        },
        saveHeader(){
            if(!this.headerContent.length){
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                this.errors.header = 'You cannot submit an empty header';
                setTimeout(this.resetStates, 150);
            }else{
                if(this.headerContent.length > this.maxChar){
                    this.$store.commit('dashboard/successStateMutation', false);
                    this.$store.commit('dashboard/failStateMutation', true);
                    this.errors.header = "Maximum " + this.maxChar + " characters";
                    setTimeout(this.resetStates, 150);
                }else{
                    this.$store.commit('dashboard/successStateMutation', true);
                    setTimeout(this.submitSave, 300);
                }
            }
        },
        submitSave(){
            this.$store.commit('dashboard/saveHFC', {name:'addHeader',data:this.headerContent})
        },
        resetStates(){
            this.$store.dispatch('dashboard/resetStatesAction')
        }
    }
    
}
</script>

<style lang="scss">


</style>
