<template>
    <div class="file-search-item">

        <div class="bread">{{bread}} <span class="last-bread"> {{this.data.breadcrumbs[this.data.breadcrumbs.length - 1]}}</span></div>
        <div class="columns">

            <div class="column">
                <div class="file-name">
                    <input :id="'search-item-id-' + data.id" class="styled-checkbox" type="checkbox" v-model="checked">
                    <label :for="'search-item-id-' + data.id"></label> {{data.name}}
                </div>
            </div>

            <div class="column">
                <div class="category-action">
                    <a v-if="isFile" :href="data.path" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <i v-if="!isFile" class="pdfglue-icon upload-file" aria-hidden="true" @click="$store.commit('manageFiles/openModal', { name: 'upload', data: data})"></i>

                    <i @click="toggleDropdown()" :data-id="dropdownId" class="pdfglue-icon context-menu" :class="{active: menu}" aria-hidden="true"></i>

                    <div v-if="menu" :id="dropdownId" class="drop-down-menu">

                        <div class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'copy', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper" > Copy To </div>
                        </div>

                        <div v-if="data.count > 1" class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'explode', data: data})">
                            <div class="drop-down-menu-item-wrapper" > Explode </div>
                        </div>

                        <div class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'edit-pdf', data: data})">
                            <div class="drop-down-menu-item-wrapper"> Edit </div>
                        </div>


                        <div v-if="isFile" class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'move', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper"> Move To</div>
                        </div>


                        <div class="drop-down-menu-item danger" @click="$store.commit('manageFiles/openModal', {name: 'delete', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper"> Delete </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    props: [
        'data',
    ],

    data() {
        return {
            menu: false,
            isFile: true,
            checked: false,
        }
    },

    computed: {
        bread() {
            //console.log(this.data)
            return this.data.breadcrumbs.slice(0, -1).join(' > ') + (this.data.breadcrumbs.length > 1 ? ' > ' : '');
        },
        dropdownId() {
            return 'dropdown-' + this.data.id;
        },
    },

    methods: {
        menuClose(ev) {
            if (ev.target.dataset.id !== this.dropdownId) {
                this.menu = false;
                window.removeEventListener('click', this.menuClose);
            }
        },
        toggleDropdown() {
            if (this.menu) {
                window.removeEventListener('click', this.menuClose);
                this.menu = false;
            } else {
                window.addEventListener('click', this.menuClose);
                this.menu = true;
            }
        },
    },

    watch: {
        checked(nVal) {
            if (nVal) {
                this.$store.commit('manageFiles/selectSearch', this.data.id);
            } else {
                this.$store.commit('manageFiles/unselectSearch', this.data.id);
            }
        }
    }

}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
.file-search-item {
    @include flex;
    @include flex-direction-column;
    &:hover {
        background: #f5f5f5;
        border-radius: 8px;
        .bread {
            background: #d9dadb;
            // border: 1px solid #d4d5d6;
            // border-top-left-radius: 8px;
            // border-top-right-radius: 8px;
        }
    }

    .bread {
        padding: 10px 20px 8px;

        min-height: 30px;
        background: #f7f7f7;
        font-size: 10px;
        color: #545454;
        font-weight: 600;
        text-transform: uppercase;
        border-bottom: 1px solid #cccccc;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;

        .last-bread {
            color: #545454;
            font-weight: 800;
        }
    }

    .columns {
        overflow: visible;
        @include flex;

        .column {
            @include flex;
            &:first-child {
                @include flex-grow(1);
            }
            &:last-child {
                @include flex-grow(0);
            }

            .file-name {
                min-height: 45px;
                color: $color-text-primary;
                font-size: 16px;
                padding: 13px 20px 15px;
                border: 0;
                // max-width: 100%;

                .styled-checkbox {
                    & + label {
                        top: -4px;
                        width: 23px;
                    }
                }
            }

            .category-action {
                position: relative;
                width: 60px;
                margin-right: 20px;
                margin-top: 10px;

                i {
                    // margin-top: 2px;
                    float: left;
                    margin-top: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    color: $color-text-light;
                    &.active,
                    &:hover {
                        color: $color-secondary;
                    }

                    &.large {
                        margin-left: 5px;
                        font-size: 20px;
                        position: relative;
                        top: 3px;
                        // margin-top: 20px;
                    }

                    &.upload-file {
                        float: left;
                        margin-left: -4px;
                        margin-right: 4px;
                        // position: relative;
                        top: -3px;
                        position: absolute;
                        right: 36px;
                        // top: 2px;
                    }

                    &.upload-file + .context-menu {
                        position: relative;
                        // top: 0px;
                        // left: -3px;
                    }

                    &.context-menu {
                        float: right;
                        position: relative;
                        // margin-left: 7px;
                        top: -3px;

                    }

                }
            }

        }
    }

}

.drop-down-menu {
    z-index: 2;
    font-family: 'Raleway';
    font-style: normal;
    font-weight: 400;
    color: #8f8f8f;
    position: absolute;
    margin-top: 0;
    background: #fff;
    @include context-menu-box-shadow;
    border-radius: 8px;
    border: 1px #cccccc solid;
    min-width: 130px;
    right: -27px;
    top: 30px;
    .drop-down-menu-item {
        color: #4c4c4c;
        text-align: right;
        cursor: pointer;

        .drop-down-menu-item-wrapper {

            margin-left: 15px;
            margin-right: 15px;
            padding-top: 5px;
            padding-bottom: 5px;
            @include fine-border(#e6e6e6, 'bottom');

        }
    }
    .drop-down-menu-item:first-child {
        margin-top: 8px;

    }
    .drop-down-menu-item:last-child {
        .drop-down-menu-item-wrapper {

            border-bottom: none;

        }
        margin-bottom: 8px;
    }
    .drop-down-menu-item:hover {
        background: #f5f5f5;
        color: $color-primary;
        &.danger {
            color: #ff3019;

        }

    }

    &::after {
        position: absolute;
        content: '';
        width: 0;
        height: 0;
        border-left: 14px solid transparent;
        border-right: 14px solid transparent;
        border-bottom: 10px solid #fff;
        top: -9px;
        right: 25px;
    }
    &::before {

        position: absolute;
        content: '';
        width: 0;
        height: 0;
        border-left: 14px solid transparent;
        border-right: 14px solid transparent;
        border-bottom: 10px solid #e6e6e6;
        top: -11px;
        right: 25px;
    }
}
</style>
