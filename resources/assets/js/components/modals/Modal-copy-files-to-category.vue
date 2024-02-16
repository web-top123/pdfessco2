<template>
<modal @close="closeModal" class="copy-modal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Copy {{ title }} To</p>
    </template>

    <template>
        <div class="form-group">
            <label>Category</label>
            <multiselect style="margin-bottom:-8px;" v-model="category" :show-labels="false" :max-height="250" placeholder="Select a category..." label="name" :options="listLimit">
                <template slot="option" scope="props">
                    <div v-bind:style="{'margin-left': (props.option.depth * 20) + 'px', 'font-weight' : 700 - (props.option.depth * 100)}">
                        {{props.option.name}}
                    </div>
                </template>
            </multiselect>
        </div>
    </template>

    <template slot="footer">
        <div class="buttons">
            <button class="button" @click="closeModal">Cancel</button>
            <button class="button is-info" @click="save">Save</button>
        </div>
    </template>

</modal>
</template>

<script>
import Modal from './Modal.vue';
import Multiselect from 'vue-multiselect';

export default {
    props: {
        name,
    },
    data() {
        return {
            category: undefined,
            listLimit: []
        }
    },
    components: {
        Modal,
        Multiselect,
    },
    computed: {
        list() {
            return this.$store.state.manageFiles.list;
        },
        data() {
            return this.$store.state.manageFiles.modals.data;
        },
        hasCategory() {
            return this.$store.state.manageFiles.selected.categories.length > 0;
        },
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
    },
    mounted() {
        if (this.data) {
            // this.category = Object.assign({}, this.data);
        }
        axios.post('/admin/copy-to/list', {categories: this.selectedArr.categories}).then( ({data}) => this.listLimit = this.list.map(item => Object.assign({'$isDisabled': item.depth > data}, item)).filter(item => this.$store.state.manageFiles.selected.categories.indexOf(item.id) < 0));
    },
    methods: {
        save() {

            const data = {
                data: {}
            };

            if (this.data) {
                if (this.data.isFile) {
                    data.data.categories = [];
                    data.data.files = [this.data.id];
                } else {
                    data.data.categories = [this.data.id];
                    data.data.files = [];
                }
                data.data.category = this.category.id;
            } else {
                data.data = {
                        categories: this.selectedArr.categories,
                        files: this.selectedArr.files,
                        category: this.category.id,
                    }
            }
            axios.patch('/admin/copy-to', data)
                .then(({data}) => {

                    if(!this.data) {
                        this.$store.commit('manageFiles/clearSelected');
                    }
                    this.$store.dispatch('manageFiles/copy', data);
                    // this.$store.dispatch('manageFiles/upload', Object.assign({isFile: false}, data));
                    this.closeModal();
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        closeModal() {
            this.$store.commit('manageFiles/closeModal', this.name);
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.copy-modal {
    .modal-card {
        overflow: visible!important;
        max-width: 100%;
        width: 520px;

        .modal-card-foot {

            flex-direction: column;

            .buttons {
                width: 100%;
                display: flex;

            }
            .separator {
                margin-top: 25px;
                border-top: 1px solid #bbbdbf;
                font-size: 14px;
                color: #bbbdbf;
                padding-top: 10px;
            }
        }
    }

    .columns {
        .column {

            margin-top: 0;
            padding-top: 0;

        }
        .column.fields {
            @include flex;
            @include flex-direction-column;
            padding-left: 20px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;

        }
    }

    .form-group {
        @include flex;
        @include flex-direction-column;
        font-family: $font-family;
        margin-bottom: 0;

        .multiselect {
            .multiselect__tags {
                padding: 0;
            }
        }

        label {
            padding-left: 15px;
            display: block;
            color: $color-text-tertiary;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 10px;
        }

        input,
        textarea {
            font-family: $font-family;
            display: block;
            @include fine-border(#cccccc);
            border-width: 2px;
            border-radius: 10px;
            color: $color-text-primary;
            font-weight: 400;
            font-size: 16px;
            padding: 15px 18px 10px;

            &:focus,
            &:hover {
                @include fine-border($color-tertiary);
                border-width: 2px;
            }
        }

        input {
            @include box-sizing(border-box);
            height: 50px;
        }

        textarea {
            resize: none;
            overflow: hidden;
            font-size: 14px;
        }

        .upload-area,
        input,
        textarea {
            &.drag,
            &:focus {
                @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
            }
        }

        .upload-area {
            @include fine-border(#cccccc);
            border-width: 2px;
            border-radius: 10px;
            width: 350px;
            height: 350px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f7;
            &.drag {
                @include fine-border($color-tertiary);
                border-width: 2px;
                @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
            }

            &:hover {
                @include fine-border($color-tertiary);
                border-width: 2px;
                .has-upload,
                .no-upload {
                    pointer-events: all;
                }
            }

            .has-upload,
            .no-upload {
                pointer-events: none;
                font-size: 14px;
                font-weight: 500;
                color: $color-text-tertiary;
                text-align: center;
                cursor: pointer;
            }



            .no-upload {
                z-index: 1;

                p {
                    margin-bottom: 20px;
                }

                button {
                    font-size: 14px;
                    font-weight: 500;
                    color: $color-text-tertiary;
                    @include fine-border(#d2d4d6);
                    border-width: 2px;
                    padding: 10px 20px;
                    width: 175px;
                    @include drop-shadow(1px, 2px, 6px, 1px, rgba(0, 0, 0, 0.06));

                    &:hover {
                        background: $color-primary;
                        color: #ffffff;
                        @include fine-border($color-primary);
                    }
                }
            }

            input {
                cursor: pointer;
                position: absolute;
                opacity: 0;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                width: 100%;
            }
        }
    }

}
</style>
