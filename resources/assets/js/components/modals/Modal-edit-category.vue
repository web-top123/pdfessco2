
<template>
<modal @close="$store.commit('manageFiles/closeModal', name)" class="move-modal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Edit Category</p>
    </template>

    <template>
        <div class="form-group">
            <label>Category Name</label>
            <input v-model="category.name" type="text" placeholder="Category name..." :class="{error: errors.name}" @keyup="errors.name = false">
        </div>
    </template>

    <template slot="footer">
        <div class="buttons">
            <button class="button" @click="$store.commit('manageFiles/closeModal', name)">Cancel</button>
            <button class="button is-info" @click="save">Save</button>
        </div>
    </template>

</modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    props: {
        name,
    },
    data() {
        return {
            category: {
                name: '',
            },
            errors: {
                name: false,
            }
        }
    },
    components: {
        Modal,
    },
    computed: {
        data() {
            return this.$store.state.manageFiles.modals.data;
        }
    },
    mounted() {
        this.category = Object.assign({}, this.data);
    },
    methods: {
        save(){
            axios.patch('/admin/categories/' + this.category.id, {'name' : this.category.name}).then(({data}) => {
                this.$store.dispatch('manageFiles/edit', Object.assign({isFile: false}, data));
                this.$store.commit('manageFiles/closeModal', this.name);
            }).catch((error) => {
                console.log(error)
                // this.errors.name = error.response.data.errors.name ? true : false;
            });
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.move-modal {
    .modal-card {
        max-width: 100%;
        width: 640px;
        overflow: visible;

        .modal-card-foot {

            flex-direction: column;
            .button.is-info{
                flex-grow: 1;
            }
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
            padding-left: 20px;
            @include flex;
            @include flex-direction-column;
            justify-content: space-between;

        }
    }

    .form-group {
        @include flex;
        @include flex-direction-column;

        font-family: $font-family;
        margin-bottom: 0;

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

.error {
    border: 2px solid #ff6756!important;
}

</style>
