<template>
    <modal :show="show" @close="$emit('close')">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">Edit Divider</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small" for="divider-name">DIVIDER NAME</label>
                <div class="control">
                    <input type="text" class="input is-medium" id="divider-name" v-model="dividerEditContent.name" placeholder="This will only be displayed in the 'New Document' list"></input>
                </div>
            </div>
            <div class="field">
                <label class="label is-small" for="divider-content">DIVIDER CONTENT<span class="char-counter"><span class="current-char" :class="{'is-danger':dividerEditContent.content.length>maxChar.content}">{{dividerEditContent.content.length}}</span> / <span class="max-char">{{maxChar.content}}</span></span></label>
                <div class="control">
                    <textarea class="textarea is-medium"  :class="{'is-danger' : dividerEditContent.content.length > maxChar.content || errors.content.length !== 0 || dividerEditContent.content.length === 0}" id="divider-content" v-model="dividerEditContent.content" placeholder="Type in content for the divider" @keyup="errors.content=''"></textarea>
                    <p class="help is-danger" :class="{'is-hidden' : dividerEditContent.content.length <= maxChar.content}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>The divider content may not be greater than {{maxChar.content}} characters.</p>
                    <p class="help is-danger" :class="{'is-hidden' : errors.content.length === 0}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.content}}</p>

                </div>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="saveDivider()">Save</button>
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
        dividerModContent:{
            type: Object,
            default: {}
        }
    },
    data(){
        return {
            dividerEditContent: {
                name:"",
                content:""
            },
            maxChar: {
                content: 500,
                name: 60
            },
            errors:{
                content:"",
                name:""
            }
        };
    },
    components: {
        Modal,
    },
    methods: {
        saveDivider(){
            if(!this.dividerEditContent.content.length){
                this.errors.content = "You cannot submit an empty divider";
            }else{
                if(this.dividerEditContent.content.length <= 500){
                    if(this.dividerEditContent.name.length == 0){
                        this.dividerEditContent.name = "Section divider";
                    }
                    this.$emit('save', this.dividerEditContent);
                }
            }
        }
    },
    watch: {
        dividerModContent() {
            this.dividerEditContent = this.dividerModContent;
        }
    }
}
</script>

<style lang="scss">


</style>
