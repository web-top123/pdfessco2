<template>
<modal class="upload-modal-dashboard" @close="closeModal">

    <template slot="header">
            <p slot="header" class="modal-card-title has-text-centered">Upload File</p>
        </template>

    <template>

        <div class="columns">
            <div class="column">
                <div class="form-group">
                    <label>Title</label>
                    <input v-model="upload.title" type="text" placeholder="Title..." :class="{error: errors.title, 'disabled-title':upload.defaultTitle}" @keyup="errors.title = false">
                    <span class="default__title--container">
                        <input v-model="upload.defaultTitle" id="default_title" type="checkbox" class="styled-checkbox">
                        <label for="default_title" style="padding-left:0"></label>Use the default file name as a title
                    </span>
                </div>
                <div class="form-group">
                    <label>Choose file</label>
                    <div class="upload-area" :class="{drag : upload.drag, error: errors.file}" @dragenter="upload.drag = true" @dragleave="upload.drag = false" @drop="upload.drag = false" >
                        <span class="wrapper" v-show="!upload.loading">
                            <div v-if="!upload.file" class="no-upload"  @click="$refs.fileInput.click();">
                                <p>Drag and Drop Files Here</p>
                                <p>or</p>
                                <button type="button" class="button-base simple">Click to Choose File</button>
                            </div>
                            <div v-if="upload.file" class="has-upload">{{ upload.file.name }}</div>
                            <form ref="fileReset"><input name="file" type="file" accept="application/pdf"  @change="processFile($event)" ref="fileInput"></form>
                        </span>

                        <span v-show="upload.loading">
                            <p class="has-upload">Uploading {{ upload.percentShow }}%</p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template slot="footer">
            <button class="button" @click="closeModal"> {{ !this.done ? 'Cancel' : 'Done' }}</button>
            <button class="button is-info" @click="more">Upload &amp; Add New</button>
            <button class="button is-info "  @click="once">Upload &amp; Close</button> 
       
        </template>

</modal>
</template>

<script>
import Modal from './Modal.vue';
import Multiselect from 'vue-multiselect';

export default {
    props: {
        name,
        width: 0
    },
    data() {
        return {
            upload: {
                drag: false,
                title: '',
                category: undefined,
                file: undefined,
                loading: false,
                percent: 0,
                percentShow: 0,
                defaultTitle: true
            },
            errors: {
                category: false,
                file: false,
                title: false,
            },
            done: false,
        }
    },
    components: {
        Modal,
        Multiselect,
    },
    computed: {
        data() {
            return this.$store.state.dashboard.modals['uploadFileDashboard'].data;
        },
    },
    mounted() {
    },
    methods: {
        more() {
            let hasErrors = false;
            if (!this.upload.title) {
                this.errors.title = true;
                hasErrors = true;
            }

            if (!this.upload.file) {
                this.errors.file = true;
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }
            this.sendUpload()
                .then( ({data}) => {
                    this.upload.loading = false;
                    this.upload.percent = 0;
                    this.upload.percentShow = 0;
                    let temp = {
                        name: this.upload.title,
                        path: data.path,
                        id: data.id
                    }
                    this.$store.commit('dashboard/selectFile', temp);
                    this.clear();
                    this.done = true;

                })
                .catch((error) => {
                    console.error(error);
                });
        },
        once() {
            let hasErrors = false;

            if (!this.upload.title) {
                this.errors.title = true;
                hasErrors = true;
            }

            if (!this.upload.file) {
                this.errors.file = true;
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }
            this.sendUpload()
                .then( ({data}) => {
                    this.upload.loading = false;
                    this.upload.percent = 0;
                    this.upload.percentShow = 0;
                    this.closeModal();
                    let temp = {
                        name: this.upload.title,
                        path: data.path,
                        id: data.id
                    }
                    this.$store.commit('dashboard/selectFile', temp);
                })
                .catch((error) => {
                    this.errors.title = error.response.data.errors.title ? true : false;
                    this.errors.file = error.response.data.errors.file ? true : false;

                    this.upload.loading = false;
                });
        },
        clear() {
            this.upload.title = '';
            this.upload.file = undefined;
            this.$refs.fileReset.reset();
        },
        processFile(event) {
            this.upload.file = event.target.files[0];
            this.errors.file = false;
            if (!this.upload.title) {
                this.upload.title = this.upload.file.name;
            }
        },
        sendUpload() {

            const data = new FormData();
            data.append('title', this.upload.title);
            data.append('category', 0);
            data.append('file', this.upload.file);

            this.upload.percent = 0;
            this.upload.loading = true;
            return axios.post('/admin/file', data, {
                onUploadProgress: ev => {
                    this.upload.percent = parseInt(ev.loaded * 100 / ev.total);
                }
            });
        },
        closeModal() {
            this.$store.commit('dashboard/closeModal', 'uploadFileDashboard');
        },
        clearErrors() {
            this.errrors = {
                title: false,
                file: false,
                category: false,
            };
        },
    },
    watch: {
        'upload.percent': function(newValue, oldValue) {
            var vm = this

            function animate() {
                if (TWEEN.update()) {
                    requestAnimationFrame(animate)
                }
            }

            new TWEEN.Tween({
                    tweeningNumber: oldValue
                })
                .easing(TWEEN.Easing.Quadratic.Out)
                .to({
                    tweeningNumber: newValue
                }, 100)
                .onUpdate(function() {
                    vm.upload.percentShow = this.tweeningNumber.toFixed(0)
                })
                .start()

            animate()
        },
        'upload.defaultTitle': function (newValue, oldValue){
            var vm = this;
            if(newValue === true && vm.upload.file){
                vm.upload.title = vm.upload.file.name;
            }

        }
    },
}
</script>

<style lang="scss">
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.upload-modal-dashboard {
    .modal-card {
        width: 520px;
        max-width: 100%;
        overflow: visible!important;

        .modal-card-foot {
            .button {
                font-weight: 500;
                padding: 10px;
            }

            .button.is-info {
                font-weight: 700;
                width: auto;
                flex-grow: 1;
                padding: 10px;
            }

        }
    }

    .columns {
        @include flex;
        .column {
            @include flex-grow(0);
            margin-top: 0;
            padding-top: 0;
            position: relative;
            max-width: 100%;
        }
        .column.fields {
            @include flex-grow(1);
            @include flex;
            @include flex-direction-column;
            padding-left: 20px;
            padding-right: 12px!important;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }
    }

    .form-group {
        @include flex;
        @include flex-direction-column;
        font-family: $font-family;
        margin-bottom: 20px;

        .multiselect {
            .multiselect__tags {
                padding: 0;
            }
        }

        .disabled-title{
            pointer-events: none;
            cursor: auto;
            background: #f8f8f8;
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
        .default__title--container{
            padding-top: 5px;

            label:before{
                margin-top: 0;
            }
            .styled-checkbox:checked + label:after{
                top: 10px;
            }
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
            font-weight: 400;
            font-size: 16px;
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
            // cursor: pointer;
            @include fine-border(#cccccc);
            border-width: 2px;
            border-radius: 10px;
            width: 400px;
            max-width: 100%;
            height: auto;
            position: relative;
            @include flex;
            @include justify-content-center;
            @include align-items(center);
            @include flex-grow(1);
            padding: 20px;
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

            .wrapper, .no-upload {
                height: 100%;
                width: 100%;

            }


            .wrapper {
                @include flex;
                @include flex-direction-column;
                @include justify-content-center;
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

            .has-upload {
                width: 100%;
                @include flex;
                @include justify-content-center;
                word-break: break-word;
            }

            .no-upload {
                @include flex;
                @include justify-content-center;
                @include flex-direction-column;
                z-index: 1;
                text-align: center;

                p {
                    margin-bottom: 20px;
                }

                button {
                    max-width: 260px;
                    margin-left: auto;
                    margin-right: auto;
                    z-index: 2;
                    height: 40px;
                    font-size: 14px;
                    font-weight: 500;
                    color: $color-text-tertiary;
                    @include fine-border(#d2d4d6);
                    padding: 10px 20px;
                    // @include box-shadow(initial);
                    &:hover {
                        color: $color-primary;
                        @include fine-border($color-primary);
                    }
                }
            }

            input {
                // z-index: -1;
                cursor: pointer;
                position: absolute;
                opacity: 0;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            @media (min-width: 780px){
                height: 300px;
            }
        }
    }

}

.error {
    border: 2px solid #ff6756!important;
}
.error-m .multiselect {
    border: 2px solid #ff6756!important;
}
</style>
