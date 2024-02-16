<template>
    <modal @close="$emit('close')" class="delete-header-confirmation">

        <template slot="header">
            <p slot="header" class="modal-card-title">Delete Footer?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the content of the footer. </p>
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
    }
}
</script>

<style lang="scss">
.modal {
    .modal-card {
        width: 460px;
    }
}

</style>
