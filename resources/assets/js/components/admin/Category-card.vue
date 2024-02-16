<template>
<div class="container">
</div>
</template>

<script>
/**
 * Components
 */
 import CategoryItem from './Category-item.vue';


/**
 * Component configuration
 */
export default {
    components: {
        CategoryItem,
    },
    data() {
        return {

        }
    },
    methods: {

    }
}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";

    .container {
        height: 200px;

        width: calc(50% - 18px);
        margin: $card-margin;
        background: #fff;
        @include fine-border(#d4d5d6);
        // @include box-shadow(0 3px 5px -1px rgba(0,0,0,.04), 0 6px 10px rgba(0,0,0,.03), 0 1px 18px rgba(0,0,0,.02));
        @include basic-box-shadow;
        border-radius: 8px;
        @include transition(box-shadow .08s ease-in-out);
        &:hover {
            @include box-shadow(0 4px 8px -1px rgba(0,0,0,.08), 0 7px 14px 1px rgba(0,0,0,.05), 0 2px 18px 2px rgba(0,0,0,.04));
        }
    }

</style>
