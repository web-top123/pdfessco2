<template>
<modal @close="$store.commit('manageFiles/closeModal', name)" class="move-modal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Explode</p>
    </template>

    <template>
        <div class="form-group">
            <label>File Name</label>
            <input id="file-name-disabled" disabled v-model="pdf.name" type="text">
        </div>
        <div class="form-group">
            <label>File Pages</label>
            <div class="explode-list" :class="{'error__no-items' : error}">
                <div class="explode-list__buttons-wrapper">
                    <div class="explode-list__buttons">
                        <button class="explode-list__button" @click="selectAll">Select All</button>
                        <button class="explode-list__button" @click="deselectAll">Deselect All</button>
                    </div>
                </div>
                <div class="explode-list__items-wrapper">
                    <div class="explode-list__items">
                        <div class="explode-list__item" v-for="p in pdf.count">
                            <span class="explode-list__item-icon">
                                <i v-if="pages.indexOf(p) > -1" class="fa fa-check check-mark" aria-hidden="true"></i>
                            </span>
                            <span class="explode-list__item-text" :class="{'explode-list__item-text--removed' : pages.indexOf(p) === -1}">Page {{p}}</span>
                            <button v-if="pages.indexOf(p) > -1" @click="toggleItem(p)" class="explode-list__item-button explode-list__item-button--remove">Remove</button>
                            <button v-if="pages.indexOf(p) === -1" @click="toggleItem(p)" class="explode-list__item-button explode-list__item-button--add">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="help is-danger error-message-flash" :class="{'is-hidden' : !error}"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Please select at least one page.</p>
        </div>
    </template>

    <template slot="footer">
        <div class="buttons">
            <button class="button" @click="$store.commit('manageFiles/closeModal', name)">Cancel</button>
            <button class="button is-info" @click="save">Explode</button>
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
            pdf: {
                name: '',
                description: '',
                user: {
                    name: ''
                },
                created_at: '',
            },
            errors: {
                name: false,
                description: false,
            },
            // count: 0,
            pages: [],
            error: false,
            request: false,
        }
    },
    components: {
        Modal,
    },
    computed: {
        data() {
            return this.$store.state.manageFiles.modals.data;
        },
    },
    mounted() {
        this.pdf = Object.assign({}, this.data);
        // this.getPages();
        for (var i = 1; i <= this.pdf.count; i++) {
            this.pages.push(i);
        }
    },
    methods: {
        save() {
            if(this.request) {
                return;
            }

            if(!this.pages.length) {
                this.error = true;
                return;
            }

            this.request = true;



            axios.post('/admin/explode', {
                id: this.pdf.id,
                pages: this.pages,
            }).then( ({data}) => {

                for (var i = 0; i < data.length; i++) {
                    this.$store.dispatch('manageFiles/upload', Object.assign({isFile: true}, data[i]));
                }

                this.$store.commit('manageFiles/closeModal', this.name)
            }).catch((error) => {
                this.request = false;
                console.error(error);
            })


            // axios.patch('/admin/file/' + this.pdf.id, {
            //     'name': this.pdf.name,
            //     'description': this.pdf.description
            // }).then(({
            //     data
            // }) => {
            //     this.$store.dispatch('manageFiles/edit', Object.assign({
            //         isFile: true
            //     }, data));
            //     this.$store.commit('manageFiles/closeModal', this.name)
            // }).catch((error) => {
            //     this.errors.name = error.response.data.errors.name ? true : false;
            //     this.errors.description = error.response.data.errors.description ? true : false;
            // });
        },
        // getPages() {
        //     axios.get('/dashboard/page-count/' + this.pdf.id)
        //         .then(resp => {
        //             this.count = resp.data.count;
        //             for (var i = 1; i <= this.count; i++) {
        //                 this.pages.push(i);
        //             }
        //
        //         })
        //         .catch(err => {
        //             console.error(err)
        //         })
        // },
        toggleItem(p) {
            const index = this.pages.indexOf(p);
            this.error = false;
            if (index > -1) {
                this.pages.splice(index, 1);
            } else {
                this.pages.push(p);
            }
        },
        selectAll() {
            this.deselectAll();
            for (var i = 1; i <= this.pdf.count; i++) {
                this.pages.push(i);
            }
        },
        deselectAll() {
            this.error = false;
            this.pages = [];
        }
    }
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.move-modal {
    width: 100%;

    .uploaded-by {

        text-align: center;
        color: $color-text-tertiary;
        span {
            font-weight: 600;
            color: $color-text-primary;
        }

    }
    .modal-card {
        max-width: 100%;
        width: 510px;
        overflow: visible;

        .modal-card-foot {
            @include flex-direction-column;

            .buttons {
                width: 100%;
                @include flex;

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
        margin-bottom: 20px;

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

.explode-label {
    font-size: 18px;
    color: #404040;
}

.explode-list {
    border: 1px solid #cccccc;
    width: 100%;
    border-radius: 8px;
    height: 200px;
    @include flex;
    @include flex-direction-column;
    .explode-list__buttons-wrapper {
        .explode-list__buttons {
            @include flex;
            @include flex-direction-row;
            border-bottom: 1px solid #cccccc;
            .explode-list__button {
                font-size: 14px;
                color: #404040;
                font-weight: 500;
                @include flex;
                @include flex-grow(1);
                @include justify-content-center;
                padding: 9px 14px;
                outline: none;
                background: none;
                border: none;
                cursor: pointer;
                &:first-child {
                    border-right: 1px solid #cccccc;
                    &:hover {
                        color: $color-primary;
                    }
                }
                &:last-child {
                    &:hover {
                        color: $danger;
                    }
                }
            }

        }
    }
    .explode-list__items-wrapper {
        @include flex;
        @include flex-direction-column;
        .explode-list__items {
            overflow-y: auto;

        // overflow-y: auto;
        // @include flex;
        // @include flex-direction-column;
        padding: 14px;
        padding-top: 0px;
        .explode-list__item {
            @include flex;
            @include flex-direction-row;
            border-bottom: 1px solid #e1e3e6;
            padding-top: 7px;
            padding-bottom: 5px;

            .explode-list__item-icon {
                @include flex;
                @include flex-grow(0);
                width: 20px;
                padding-top: 3px;
            }

            .explode-list__item-text {
                @include flex;
                @include flex-grow(1);
                font-size: 16px;
                color: #404040;
                &.explode-list__item-text--removed {
                    color: #cccccc;
                }
            }

            .explode-list__item-button {
                @include box-sizing(border-box);
                width: 50px;
                @include flex;
                @include flex-grow(0);
                font-family: $font-family;
                outline: none;
                border: none;
                background: none;
                cursor: pointer;
                font-size: 12px;
                font-weight: 500;
                letter-spacing: 0.2px;

                &.explode-list__item-button--add {
                    color: $color-primary;
                }

                &.explode-list__item-button--remove {
                    color: $danger;
                }
            }
        }
    }
    }
}
#file-name-disabled:disabled {
    background: #f8f8f8;
}
</style>
