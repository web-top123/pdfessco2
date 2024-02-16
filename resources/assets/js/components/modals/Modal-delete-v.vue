<template>
    <div class="modal" v-if="show">

        <div class="modal-background" @click="close">
            <i class="fa fa-close" @click="close"></i>
        </div>

        <div class="modal-card">

            <header class="section modal-card-head">
                <p class="modal-card-title small">Delete File?</p>
            </header>

            <section class="section modal-card-body">


            </section>

            <footer class="section modal-card-foot">


            </footer>

        </div>

    </div>
</template>

<script>
export default {
    computed: {
        show() {
            return this.$store.state.manageFiles.modals.delete;
        }
    },
    methods: {
        close() {
            this.$store.commit('manageFiles/closeModal', 'delete');
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
@import "resoucess/assets/sass/modals";



</style>



<!-- <template>
    <modal :show="show">

        <template slot="header">
            <p slot="header" class="modal-card-title">Delete Items?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the selected items. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="confirmedDelete()">Delete</button>
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
        data: {
            default: undefined,
        },
        selected: {
            default: undefined,
        }
    },
    components: {
        Modal,
    },
    methods: {
        confirmedDelete(){
            const self = this;
            let data = {};
            if (self.data) {

                data = self.data;

            } else {

                data = self.selected;

            }

            console.log(data, data.categories, data.files);

            axios.post('/admin/categories/delete', {data: data})
            .then(() => {
                self.$emit('delete');
                self.$emit('close');
            })
            .catch((error) => console.error(error));
        }
    },
}
</script>

<style lang="scss">


</style> -->
