<template>
    <modal @close="$emit('close')" class="delete-header-confirmation">
        
        <template slot="header">
            <p slot="header" class="modal-card-title">Start Over?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? All progress will be lost. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="$emit('save')">Yes</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    components: {
        Modal,
    }
}
</script>

<style lang="scss">


</style>
