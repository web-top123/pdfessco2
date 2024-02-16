<template>
    <div class="modal is-active">

        <div class="modal-background" @click="$emit('close')">
            <i aria-label="close" class="delete fa fa-close" @click="$emit('close')"></i>
        </div>

        <div class="modal-card">

            <header class="modal-card-head">
                <slot name="header"></slot>
            </header>

            <section class="modal-card-body">
                <slot></slot>
            </section>

            <footer class="modal-card-foot">
                <slot name="footer"></slot>
            </footer>

        </div>

    </div>
</template>

<style lang="scss" scoped>
    // @import "resources/assets/sass/modals";
    @import "resources/assets/sass/variables";
    // @import "~bulma/bulma";

    .modal {
        z-index: 99999;
        font-family: Raleway;
    }
    .char-counter{
        float: right;
        .current-char{
            color: #404040
        }
        .current-char.is-danger{
            color: #ff3019
        }
        .current-char.is-ok{
            color: #1bb267
        }
    }
    .input, .textarea, .button{
        font-weight: 400;
        color: $color-text-primary;
        &:focus,&:active {
            border-color: $color-tertiary
        }
        &.is-success,&.is-success:focus, &.is-success:active{
            border-color: #7acca3
        }
        &.is-danger,&.is-danger:focus, &.is-danger:active{
            border-color: #ff7566
        }
    }
    .button{
        color: #fff;
        height: 50px;
    }
</style>
