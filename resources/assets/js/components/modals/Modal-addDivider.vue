<template>
    <modal @close="$emit('close')" class="add-divider">

        <template slot="header">
            <p slot="header" class="modal-card-title">Insert Divider</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small" for="divider-name">DIVIDER NAME</label>
                <div class="control">
                    <input type="text" class="input is-medium" id="divider-name" v-model="dividerData.name" placeholder="This will only be displayed in the 'New Document' list"></input>
                </div>
            </div>
            <div class="field">
                <label class="label is-small" for="divider-content">DIVIDER CONTENT<span class="char-counter"><span class="current-char" :class="{'is-danger':dividerData.content.length>maxChar.content}">{{dividerData.content.length}}</span> / <span class="max-char">{{maxChar.content}}</span></span></label>
                <div class="control">
                    <textarea rows="2"
                              placeholder="Type in content for the divider"
                              class="textarea is-medium"
                              :class="{'is-danger' : (typeof dividerData.content !== 'undefined' && dividerData.content.length > maxChar.content || errors.content.length > 0), 'is-success': successState === true, 'bounce-enter-active': failState === true}"
                              id="divider-content"
                              v-model="dividerData.content"
                              @keyup="errors.content=''; $store.commit('dashboard/failStateMutation',false)"></textarea>
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="dividerData.content.length > maxChar.content">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>Maximum {{maxChar.content}} characters ( {{dividerData.content.length-maxChar.content}} too many ).
                    </p>
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.content.length > 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.content}}.
                    </p>
                </div>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" :class="{'is-success':successState===true }" >
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="saveDivider">Save</span>
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
            dividerData:this.$store.state.dashboard.modals.addDivider.data,
            maxChar: {
                content: 500,
                name: 60
            },
            errors:{
                content: "",
                name: ""
            },
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
    },
    methods: {
        saveDivider(){
            if(!this.dividerData.content.length){
                this.errors.content = "You cannot submit an empty divider";
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                setTimeout(this.resetStates, 150);
               
            }else{
                if(this.dividerData.content.length <= this.maxChar.content){
                    if(this.dividerData.name.length == 0){
                        this.dividerData.name = "Section divider";
                    }
                    this.$store.commit('dashboard/successStateMutation', true);
                    setTimeout(this.submitSave, 300);
                    this.$emit('close');
                }
                else
                {
                    this.$store.commit('dashboard/successStateMutation', false);
                    this.$store.commit('dashboard/failStateMutation', true);
                    setTimeout(this.resetStates, 150);
                }
            }
        },
        submitSave(){
            //console.log(this.dividerData)
            this.$store.commit('dashboard/insertDivider', this.dividerData);
        },
        resetStates(){
            this.$store.dispatch('dashboard/resetStatesAction')
        }
    }
}
</script>

<style lang="scss">
.modal{
    &.add-divider{
        .modal-card-foot{
            .button{
                &.is-info{
                    flex-grow: 1;
                }
            }
        }

    }
}

</style>
