<template>
    <modal @close="$emit('close')" class="add-cover-modal">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">{{coverState}} Cover</p>
        </template>

        <template>
            <div class="field">
                <label class="label is-small" for="cover-type">PROJECT TYPE <span class="char-counter"><span class="current-char" :class="{'is-danger':coverContent.projectType.length>maxChar.projectType}">{{coverContent.projectType.length}}</span> / <span class="max-char"></span>{{maxChar.projectType}}</span></label>
                <div class="control">
                    <input type="text"
                            placeholder="Submittal #1" 
                            class="input is-medium" 
                            :class="{'is-danger' : (coverContent.projectType.length > maxChar.projectType || errors.projectType.length !== 0), 'is-success': successState === true, 'bounce-enter-active': failState === true}" 
                            id="cover-type" 
                            v-model="coverContent.projectType" 
                            @keyup="checkInput('projectType')" />
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.projectType.length !== 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.projectType}} <span v-if="coverContent.projectType.length>maxChar.projectType"> ({{ coverContent.projectType.length-maxChar.projectType }} too many).</span>
                    </p>
                </div>
            </div>
            <div class="field">
                <label class="label is-small" for="cover-content">PROJECT NAME <span class="char-counter"><span class="current-char" :class="{'is-danger':coverContent.project.length>maxChar.project}">{{coverContent.project.length}}</span> / <span class="max-char"></span>{{maxChar.project}}</span></label>
                <div class="control">
                    <input type="text"
                            placeholder="DD1 Pumpwell (S17B) General Repairs, JBPHH, Oahu, Hawaii" 
                            class="input is-medium" 
                            :class="{'is-danger' : (coverContent.project.length > maxChar.project || errors.project.length !== 0), 'is-success': successState === true, 'bounce-enter-active': failState === true}" 
                            id="cover-content" 
                            v-model="coverContent.project" 
                            @keyup="checkInput('project')" />
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.project.length !== 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.project}} <span v-if="coverContent.project.length>maxChar.project"> ({{ coverContent.project.length-maxChar.project }} too many).</span>
                    </p>
                </div>
            </div>
            <div class="field">
                <label class="label is-small" for="customer-content">CUSTOMER NAME <span class="char-counter"><span class="current-char" :class="{'is-danger':coverContent.customer.length>maxChar.customer}">{{coverContent.customer.length}}</span> / <span class="max-char"></span>{{maxChar.customer}}</span></label>
                <div class="control">
                    <input type="text"
                            placeholder="NAN Inc., 636, Laumaka St., Honolulu, Hawaii, 96819" 
                            class="input is-medium" 
                            :class="{'is-danger' : (coverContent.customer.length > maxChar.customer || errors.customer.length !== 0), 'is-success': successState === true, 'bounce-enter-active': failState === true}" 
                            id="customer-content" 
                            v-model="coverContent.customer" 
                            @keyup="checkInput('customer')" />
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.customer.length !== 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.customer}} <span v-if="coverContent.customer.length>maxChar.customer"> ({{ coverContent.customer.length-maxChar.customer }} too many). </span>
                    </p>
                </div>
            </div>
            <div class="field">
                <label class="label is-small" for="job-content">JOB NUMBER <span class="char-counter"><span class="current-char" :class="{'is-danger':coverContent.job.length>maxChar.job}">{{coverContent.job.length}}</span> / <span class="max-char"></span>{{maxChar.job}}</span></label>
                <div class="control">
                    <input type="number"
                            placeholder="17051" 
                            class="input is-medium" 
                            :class="{'is-danger' : coverContent.job.length > maxChar.job , 'is-success': successState === true, 'bounce-enter-active': failState === true}" 
                            id="job-content" 
                            v-model="coverContent.job" 
                            @keyup="checkInput('job')" />
                    <p class="help is-danger error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="errors.job.length !== 0">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.job}} <span v-if="coverContent.job.length>maxChar.job"> ({{ coverContent.job.length-maxChar.job }} too many). </span>
                    </p>
                </div>
            </div>
        </template>

        <template slot="footer">
            <a class="is-delete" :class="{'disabled-delete':coverState=='Add'}"><span @click="$emit('delete')" class="is-delete-link" >Delete Cover</span></a>
            <button class="button" @click="cancelCover">Cancel</button> 
            <button class="button is-info" :class="{'is-success':successState===true }" @click="saveCover">
                <transition name="bounce">
                    <span class="save-button" v-if="successState === false" key="editing" @click="saveCover">Save</span> 
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
            maxChar: {
                projectType: 70,
                project: 140,
                customer: 140,
                job: 10
            },
            errors:{
                projectType: '',
                project: '',
                customer: '',
                job: '',
            },
            coverContent: { 
                projectType: this.$store.state.dashboard.modals.addCover.content.projectType,
                project:this.$store.state.dashboard.modals.addCover.content.project, 
                customer: this.$store.state.dashboard.modals.addCover.content.customer, 
                job: this.$store.state.dashboard.modals.addCover.content.job
            }
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
        coverExists(){
            return this.$store.state.dashboard.modals.addCover.exists;
        },
        coverState(){
            return !this.$store.state.dashboard.modals.addCover.exists ? 'Add' : 'Edit';
        }

    },
    methods: {
        cancelCover(){
            this.$emit('close');
        },
        checkInput( type ){
            this.errors[type] = '';
            if(type === 'project'){
                if(this.coverContent.customer.length <= this.maxChar.customer){
                    this.errors.customer = '';
                }
                if(this.coverContent.projectType.length <= this.maxChar.projectType){
                    this.errors.projectType = '';
                }
            }
            if(type === 'customer'){
                if(this.coverContent.project.length <= this.maxChar.project){
                    this.errors.project = '';
                }
                if(this.coverContent.projectType.length <= this.maxChar.projectType){
                    this.errors.projectType = '';
                }
            }
            if(type === 'projectType'){
                if(this.coverContent.customer.length <= this.maxChar.customer){
                    this.errors.customer = '';
                }
                if(this.coverContent.project.length <= this.maxChar.project){
                    this.errors.project = '';
                }
            }
            if(this.coverContent[type].length > this.maxChar[type]){
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                this.errors[type] = "Maximum " + this.maxChar[type] + " characters";
                setTimeout(this.resetStates, 150);
            }else{
                this.$store.commit('dashboard/failStateMutation', false);
            }
        },
        saveCover(){
            if(!this.coverContent.project.length && !this.coverContent.customer.length && !this.coverContent.projectType.length){
                var err = "You should fill in at least one of the highlighted fields.";
                this.errors.project = err;
                this.errors.projectType = err;
                this.errors.customer = err;
                this.$store.commit('dashboard/successStateMutation', false);
                this.$store.commit('dashboard/failStateMutation', true);
                setTimeout(this.resetStates, 150);
            }else{
                if(this.coverContent.project.length <= this.maxChar.project && this.coverContent.customer.length <= this.maxChar.customer && this.coverContent.job.length <= this.maxChar.job && this.coverContent.projectType.length <= this.maxChar.projectType){
                    this.errors.project = "";
                    this.errors.projectType = "";
                    this.errors.customer = "";
                    this.errors.job = "";
                    this.$store.commit('dashboard/successStateMutation', true);
                    setTimeout(this.submitSave, 300);
                }else{
                    if(this.coverContent.project.length >= this.maxChar.project){
                        this.errors.project = "Maximum " + this.maxChar.project + " characters";
                    }
                    if(this.coverContent.customer.length >= this.maxChar.customer){
                        this.errors.customer = "Maximum " + this.maxChar.customer + " characters";
                    }
                    if(this.coverContent.job.length >= this.maxChar.job){
                        this.errors.job =  "Maximum " + this.maxChar.job + " characters";
                    }
                    if(this.coverContent.projectType.length >= this.maxChar.projectType){
                        this.errors.projectType =  "Maximum " + this.maxChar.projectType + " characters";
                    }
                    this.$store.commit('dashboard/successStateMutation', false);
                    this.$store.commit('dashboard/failStateMutation', true);
                    setTimeout(this.resetStates, 150);
                }
            }
        },
        submitSave(){
            this.$store.commit('dashboard/saveHFC', {name:'addCover',data:this.coverContent})
        },
        resetStates(){
            this.$store.dispatch('dashboard/resetStatesAction')
        }
    }
}
</script>