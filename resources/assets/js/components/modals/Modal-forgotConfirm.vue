<template>
    <modal @close="$emit('close')" class="change-pass-container forgot-confirm-container">

        <template slot="header">
            <p slot="header" class="modal-card-title">Check Your Email</p>
        </template>

        <template>
            <div class="confirm-reset">
                Success! The password reset link was sent to your email address. It should be there momentarily.
            </div>
            <div class="confirm-reset boldit">
                Please check your email and click the link in the message.  After 4 hours, the link will expire.
            </div>
        </template>

        <template slot="footer">
            <a class="button is-info" href="/dashboard">Back to Log In</a>
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
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.change-pass-container.forgot-confirm-container{
    .confirm-reset{
        text-align: center;
    }
    .confirm-reset:first-of-type{
        margin-bottom: 25px;
    }
    .confirm-reset.boldit{
        font-weight: 600;
    }
    .modal-card-foot a.button:first-of-type{
        color: #fff;
        &:hover{
            color: #fff;
        }
    }
}

</style>
