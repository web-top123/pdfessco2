<template>
    <modal class="delete-header-confirmation" @close="$store.commit('manageFiles/closeModal', name)">

        <template slot="header">
            <p slot="header" class="modal-card-title">Delete {{title}}?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the selected {{title.toLowerCase()}}. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button" @click="$store.commit('manageFiles/closeModal', name)">Cancel</button>
            <button class="button is-info" @click="confirmedDelete()">Delete</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    props: {
        selected: {
            default: undefined,
        },
        name,
    },
    components: {
        Modal,
    },
    computed: {
        selectedArr() {
            return this.$store.state.manageFiles.search.query ? this.$store.state.manageFiles.search.selected : this.$store.state.manageFiles.selected;
        },
        title() {

            if (this.data) {

                if (this.data.isFile) {

                    return 'File';

                }

                return 'Category';

            } else if (this.selectedArr.categories.length) {

                if (!this.selectedArr.files.length) {

                    if (this.selectedArr.categories.length > 1) {

                        return 'Categories';

                    }

                    return 'Category';

                } else {

                    return 'Items';

                }

            } else {

                if (this.selectedArr.files.length > 1) {

                    return 'Files';

                } else if (this.selectedArr.files.length === 1){

                    return 'File';

                }

            }

            return 'Items';
        },
        data() {
            return this.$store.state.manageFiles.modals.data;
        }
    },
    methods: {
        confirmedDelete(){

            let data = {};

            if (this.data) {

                if (!this.data.isFile) {

                    data = {
                        categories: [this.data.id],
                        files: [],
                    }

                } else {

                    data = {
                        files: [this.data.id],
                        categories: [],
                    }

                }

            } else {

                data = this.selectedArr;

            }

            axios.post('/admin/categories/delete', {data: data})
            .then(() => {

                if(!this.data) {
                    this.$store.commit('manageFiles/clearSelected');
                }

                this.$store.dispatch('manageFiles/delete', data);
                this.$store.commit('manageFiles/closeModal', this.name);
            })
            .catch((error) => console.error(error));
        }
    },
}
</script>
