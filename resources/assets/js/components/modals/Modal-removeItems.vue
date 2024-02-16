<template>
    <modal @close="$emit('close')" class="delete-header-confirmation">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">Remove items?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the items from the list. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="$emit('save')">Delete</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    components: {
        Modal,
    },
    methods:{
        remove(){
            this.$store.commit('dashboard/removeItems')
        }
    }
}
</script>

<style lang="scss">


</style>
