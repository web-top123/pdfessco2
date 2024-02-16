<template>
    <modal @close="$emit('close')" class="delete-header-confirmation">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">Remove {{data.type}}?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the {{data.type}} from the list. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="$emit('save', data)">Delete</button> 
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    data() {
        return {data:this.$store.state.dashboard.itemSelected }
    },
    components: {
        Modal,
    },
    methods:{
        removeItem(){
            this.$store.commit('dashboard/removeItem', this.data)
        }
    }
}
</script>

<style lang="scss">


</style>
