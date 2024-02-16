
<template>
<modal :show="show" @close="$emit('close')" class="move-modal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Edit User</p>
    </template>

    <template>
        <div class="form-group">
            <label>Name</label>
            <input v-model="edit.name" type="text" placeholder="Firstname Lastname" @keyup="errors.name = ''">
            <p class="help is-danger" :class="{'is-hidden' : !errors.name.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.name}}</p>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input v-model="edit.email" type="text" placeholder="example@domain.com" @keyup="errors.email = ''">
            <p class="help is-danger" :class="{'is-hidden' : !errors.email.length}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{errors.email}}</p>
        </div>
    </template>

    <template slot="footer">
        <div class="buttons">
            <button class="button" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="save">Save</button>
        </div>
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
        user: {
            default: undefined,
        },
    },
    data() {
        return {
            edit: {
                name: '',
                email: '',
            },
            errors:{
                name:'',
                email:''
            }
        }
    },
    mounted() {
        this.edit  = Object.assign({}, this.user);
    },
    components: {
        Modal,
    },
    methods: {
        save(){
            axios.patch('/admin/user/' + this.edit.id, {'name' : this.edit.name, 'email' : this.edit.email}).then(({data}) => {
                this.$emit('edit', data);
                this.$emit('close');
            }).catch(({response}) => {
                if(response.data.errors && response.data.errors.name) {
                    this.errors.name = response.data.errors.name[0];
                }
                if(response.data.errors && response.data.errors.email) {
                    this.errors.email = response.data.errors.email[0];
                }
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
        width: auto;
        overflow: visible;
        max-width: 510px;

        .modal-card-body {
            .field{
                margin-bottom: 25px!important;

            }
            input {
                @include box-sizing(border-box);
                height: 50px!important;
                // margin-bottom: -5px!important;
            }
        }

        .modal-card-foot {

            padding-top: 10px!important;
padding-bottom: 60px!important;
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
            padding-left: 30px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;

        }
    }

    .form-group {
        font-family: $font-family;
        margin-bottom: 25px;

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
            width: 100%;
            font-weight: 400;
            font-size: 16px;
            width: 350px;
            // min-width: 350px;
            max-width: 100%;

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
