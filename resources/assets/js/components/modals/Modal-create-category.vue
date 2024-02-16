<template>
<modal @close="closeModal" class="create-modal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Create New Category</p>
    </template>

    <template>

        <div class="form-group">
            <label>Category Name</label>
            <input v-model="created.name" type="text" placeholder="Title..." :class="{error: errors.name}" @keyup="errors.name = false">
        </div>

        <div class="form-group">
            <label>Select Parent Category</label>
            <span :class="{'error-m': errors.category}" @click="errors.category = false">
            <multiselect style="margin-bottom:-8px;" v-model="created.category" :show-labels="false" :max-height="250" placeholder="Select a category..." label="name" :options="list">
                <template slot="option" scope="props">
                    <div v-bind:style="{'margin-left': (props.option.depth * 20) + 'px', 'font-weight' : 700 - (props.option.depth * 100)}">
                        {{props.option.name}}
                    </div>
                </template>
                <span slot="noResult"> No results </span>
            </multiselect>
            </span>
    </div>
    </template>

    <template slot="footer">
            <button class="button" @click="closeModal">Cancel</button>
            <button class="button is-info" @click="save">Save</button>
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
            created: {
                name: '',
                category: undefined,
            },
            errors: {
                category: false,
                name: false,
            },
        }
    },
    components: {
        Modal,
        Multiselect,
    },
    computed: {
        list() {

            return [{
                id: 0,
                name: 'Create as main category',
                depth: 0
            }].concat(this.$store.getters['manageFiles/createList']);

        },
        data() {
            return this.$store.state.manageFiles.modals.data;
        }
    },
    mounted() {
        if (this.data) {
            this.created.category = Object.assign({}, this.data);
        }
    },
    methods: {
        save() {

            let hasErrors = false;

            if (!this.created.name) {
                this.errors.name = true;
                hasErrors = true;
            }


            if (hasErrors) {
                return;
            }

            axios.post('/admin/categories', {
                id: this.created.category.id,
                name: this.created.name
            }).then(({
                data
            }) => {
                this.$store.dispatch('manageFiles/add', Object.assign({isFile: false}, data));
                this.closeModal();
            }).catch((error) => {
                this.errors.name = error.response.data.errors.name ? true : false;
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
.create-modal {
    .modal-card {
        width: auto;
        overflow: visible!important;
        max-width: 510px;
        width: auto;

    }

    .form-group {
        font-family: $font-family;
        margin-bottom: 30px;
        @include flex;
        @include flex-direction-column;
        &:last-child {
            margin-bottom: 0;
        }

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

        input {
            @include box-sizing(border-box);
            height: 50px;
        }

        input,
        textarea {
            font-family: $font-family;
            display: block;
            @include fine-border(#cccccc);
            border-width: 2px;
            border-radius: 10px;
            padding: 15px 18px 10px;
            color: $color-text-primary;
            // width: 100%;
            font-weight: 400;
            font-size: 16px;
            // width:100%;
            // min-width: 350px;
            &:focus,
            &:hover {
                @include fine-border($color-tertiary);
                border-width: 2px;
            }
        }

        textarea {
            resize: none;
            overflow: hidden;
            font-size: 14px;
        }

        input,
        textarea {
            &.drag,
            &:focus {
                @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
            }
        }
    }

    .error {
        border: 2px solid #ff6756!important;
    }
    .error-m .multiselect input {
        border: 2px solid #ff6756!important;
        // border-radius: 10px;
        // margin-bottom: -8px;
    }

}


</style>
