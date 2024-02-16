<template>
    <transition name="fade-item" mode="out-in">
<div>
    <div class="category-wrapper" :style="'padding-left:' +  padLeft">
        <div class="category-border-bottom columns" :class="{main: topLevel, 'no-items' : noItems, 'is-last' : !topLevel && isLast, 'not-expanded': !expanded}" >
            <div class="column is-narrow left-action-col">

                <span class="expand-icon" :class="{'no-items': (!data.children_count && !data.files_count)}" @click="expandCat">
                        <i class="fa caret fa-caret-right fa-stack-1x" :class="{expanded: expanded}"></i>
                        <!-- <i v-if="expanded" class="fa caret fa-caret-down fa-stack-1x"></i> -->
                </span>

                <input :id="checkId + '-d'" class="styled-checkbox" v-if="parentChecked" type="checkbox" checked disabled>
                <input :id="checkId + '-a'" class="styled-checkbox" v-if="!parentChecked" type="checkbox" :checked="checked" @click="toggle">
                <label class="disabled" v-if="parentChecked" :for="checkId + '-d'"></label>
                <label v-if="!parentChecked" :for="checkId + '-a'"></label>

            </div>
            <div class="column" @click="expandCat">
                <p class="category-name column" :class="{'is-file' : isFile, 'checked' : checked || parentChecked, 'expand' : data.children_count || data.files_count}" ref="categoryName">{{ data.name }}</p>
            </div>
            <div class="column is-narrow">
                <div class="category-action">
                    <a v-if="isFile && width > 1024" :href="data.path" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>

                    <i v-if="!isFile && width > 1024" class="pdfglue-icon upload-file" aria-hidden="true"  @click="$store.commit('manageFiles/openModal', { name: 'upload', data: data})"></i>

                    <i @click="toggleDropdown()" :data-id="dropdownId" class="pdfglue-icon context-menu" :class="{active: menu}" aria-hidden="true"></i>

                    <div class="menu-icon"></div>

                    <div v-if="menu" :id="dropdownId" class="drop-down-menu">

                        <div v-if="!isFile && data.depth < 3" class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', {name: 'create', data: data})">
                            <div class="drop-down-menu-item-wrapper"> Add Category </div>
                        </div>

                        <div v-if="!isFile && !topLevel" class="drop-down-menu-item" @click="moveUp">
                            <div class="drop-down-menu-item-wrapper"> Move Up</div>
                        </div>

                        <div class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'move', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper"> Move To</div>
                        </div>

                        <div v-if="isFile" class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'copy', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper" > Copy To </div>
                        </div>

                        <div v-if="isFile && data.count > 1" class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: 'explode', data: data})">
                            <div class="drop-down-menu-item-wrapper" > Explode </div>
                        </div>

                        <div class="drop-down-menu-item" @click="$store.commit('manageFiles/openModal', { name: (!isFile ? 'edit-category' : 'edit-pdf'), data: data})">
                            <div class="drop-down-menu-item-wrapper"> Edit </div>
                        </div>

                        <div v-if="isFile && width <= 1024" class="drop-down-menu-item">
                            <div class="drop-down-menu-item-wrapper" > <a :href="data.path" target="_blank">View File </a></div>
                        </div>


                        <div class="drop-down-menu-item danger" @click="$store.commit('manageFiles/openModal', {name: 'delete', data: Object.assign({isFile: isFile}, data)})">
                            <div class="drop-down-menu-item-wrapper"> Delete </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="category-level" v-if="expanded">
        <category-item v-if="category.hide !== true" v-for="(category, index) in data.children" :key="category.id" :data="category" :parent="data" :parent-checked="checked || parentChecked" :last="last && (index === data.children.length - 1) && (!data.files || !data.files.length)"></category-item>
        <category-item v-for="(file, index) in data.files" :key="file.id" :data="file" :parent="data" :parent-checked="checked || parentChecked" :is-file="true" :last="last && (index === data.files.length - 1)"></category-item>
    </div>
</div>
</transition>
</template>

<script>
export default {

    props: {
        data: {
            type: Object,
            required: true,
        },
        isFile: {
            type: Boolean,
            default: false,
        },
        topLevel: {
            type: Boolean,
            default: false,
        },
        parentChecked: {
            type: Boolean,
            default: false,
        },
        parent: {
            type: Object,
            default: () => {},
        },
        last: {
            default: false,
        },
        parentLast: {
            default: true,
        },
        lastInArray: {
            type: Boolean,
        }
    },
    data() {
        return {
            checked: false,
            menu: false,
            padding: 25,
            expanded: false,
            expandedLoaded: false,
            width: 0,
        }
    },
    mounted() {
        if (!this.isFile && this.selected.categories.indexOf(this.data.id) > -1) {
            this.checked = true;
        }
        this.width = document.body.clientWidth;
        window.addEventListener('resize', (e) => {
            this.width = document.body.clientWidth;
        })

        if(this.isFile && this.data.count === undefined) {
            this.getPages();
        }
    },
    created() {
        if (!this.topLevel && !this.isFile) {
            this.parent.files_count += this.data.files_count;
        }

        if (this.getArr().indexOf(this.data.id) > -1 || this.parentChecked) {
            this.checked = true;
        }
    },
    beforeCreate: function() {
        this.$options.components.CategoryItem = require('./Category-item.vue').default;
    },
    computed: {
        checkId() {
            return 'check-' + (this.isFile ? 'file-' : 'cat-') + this.data.id;
        },
        dropdownId() {
            return 'dropdown-' + this.data.id;
        },
        categoryText() {
            return this.isFile ? 'file-text' : 'sub-category-text-' + this.data.depth;
        },
        padLeft() {
            if (this.topLevel) {
                return 0;
            }
            if (this.isFile) {
                return (this.padding * (this.parent.top ? 1 : (this.parent.depth + 1))) + 'px';
            } else {
                return (this.padding * this.data.depth || 0) + 'px';
            }
        },
        // noItems() {
        //     return false;
        // },
        // ancestorLast() {
        //     // return this.last && (index === data.children.length - 1) && (!category.children || !category.children.length) && (!data.files || !data.files.length)
        //     return this.last && this.lastInArray;
        // },
        isLast() {
            return this.last && (!this.data.children || !this.data.children.length) && (!this.data.files || !this.data.files.length);
        },
        noItems() {
            return !this.isFile && ((this.expandedLoaded && !this.expanded) || (this.topLevel && (!this.data.files || !this.data.files.length) && (!this.data.children|| !this.data.children.length)));
        },
        // isLast() {
        //     return this.isFile ? this.parentLast && this.last : !this.topLevel && ((this.last && this.parentLast)); //this.last && this.parentLast && ( (this.expandedLoaded && !this.expanded) ||  ((!this.data.files || !this.data.files.length) && (!this.data.children || !this.data.children.length)));
        // },
        // isLast() {
        //     if (this.isFile) {
        //
        //         return this.last;
        //
        //     }
        //
        //     return this.last;
        // },
        selected() {
            return this.$store.state.manageFiles.selected;
        },
    },
    watch: {
        parentChecked(val) {
            if (val) {
                this.checked = true;
            } else {
                this.remove();
                this.checked = false;
            }
        },
        data() {
            if (this.getArr().indexOf(this.data.id) > -1) {
                this.checked = true;
            } else {
                this.checked = false;
            }
            this.menu = false;
        },
        selected() {
            if (!this.selected.categories.length && !this.selected.files.length) {
                this.checked = false;
            }
        },
    },
    methods: {

        toggle() {
            this.checked = !this.checked;
            if (this.checked) {
                this.add();
            } else {
                this.remove();
            }
        },
        add() {
            return this.getArr().push(this.data.id);
        },
        remove() {
            return this.getArr().splice(this.getArr().indexOf(this.data.id), 1);
        },
        getArr() {
            return this.isFile ? this.selected.files : this.selected.categories;
        },
        closeMenu() {
            return this.menu = false;
        },
        openModal(type, data) {
            this.menu = false;
            return this.$emit('open', {
                type: type,
                data: data
            });
        },
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
        moveUp() {
            this.$store.dispatch('manageFiles/deleteCategory', this.data)
            .then( () =>
            axios.patch('/admin/categories/moveUp', {category: this.data.id})
                .then(({data}) => this.$store.dispatch('manageFiles/moveCategory', data) )
            );
        },
        expandCat() {
            if (!this.data.children_count && !this.data.files_count) {
                return
            }

            if (!this.expandedLoaded) {
                this.$store.dispatch('manageFiles/loadCategory', this.data)
                .then( () => {
                    this.expanded = !this.expanded;
                    this.expandedLoaded = true;
                })
            } else {
                this.expanded = !this.expanded;
            }

            this.$emit('expanded')
        },
        getPages() {
            axios.get('/dashboard/page-count/' + this.data.id)
                .then(resp => {
                    this.data.count = resp.data.count;

                })
                .catch(err => {
                    console.error(err)
                })
        },
    }
}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";

.columns {
    @include flex;
    justify-content: flex-start;

    .column {
        @include flex-grow(1);
    }

    .is-narrow {
        @include flex-grow(0);
    }

}

.category-wrapper {
    &:hover {
        background-color: #f5f5f5;
        border-radius: 8px;
    }
    padding-left: 20px;
}

.category-border-bottom {
    margin: 0;
    padding: 5px 0;
    margin-left: 15px;
    margin-right: 20px;
    padding-bottom: 7px;
    overflow: visible;
    @include fine-border(#e6e6e6, 'bottom');

    &.main {
        @include fine-border(#cccccc, 'bottom');
        padding-left: 20px;
        padding-top: 12px;
        margin-left: 0;
        padding-right: 20px;
        margin-right: 0;
        padding-bottom: 15px;
        &.not-expanded {
            border-bottom: 0;
        }
    }

    &.no-items {
        padding-bottom: 12px;
    }

    &.is-last {
        border-bottom: none;
        margin-bottom: 0px;
        padding-bottom: 15px;

    }
}

.left-action-col {
    padding-left: 15px;
    position: relative;

    input {
        float: left;
        margin-top: 4px;
    }


    .expand-icon {
        // width: 100%;
        cursor: pointer;
        // top: -6px;
        top: 2px;
        font-size: 20px;
        position: absolute;
        left: -5px;
        float: left;
        color: $color-text-tertiary;
        width: 10px;
        height: 10px;
        &:hover {
            color: $color-primary;
        }
        .caret {
            @include transition(all .1s linear);
            transform: rotate(0deg);
        }
        .caret.expanded {
                transform: rotate(90deg);
                // left: 3px;
                // top: -2px;
                top: 1px;
                // left: -4px;
        }

        &.no-items {
            cursor: none;
            pointer-events: none;
            opacity: 0;
        }

    }
}

.category-name {

    font-family: $font-family;
    color: $color-text-primary;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    padding-top: 4px;
    margin-left: -2px;


    &.is-file {
        font-weight: 400;
        // font-style: italic;
    }

    &.checked {
        color: $color-primary;
    }

    &.expand {
        cursor: pointer;
    }
}

.category-action {
    text-align: right;
    position: relative;
    width: 60px;

    i {
        // margin-top: 2px;
        float: left;
        margin-top: 5px;
        cursor: pointer;
        font-size: 16px;
        color: $color-text-light;
        &:hover, &.active {
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
            float:right;
            position: relative;
            // margin-left: 7px;
            top: -3px;

        }

        // @media (max-width: 550px) {
        //     font-size: 10px;
        //     &.large {
        //         font-size: 10px;
        //     }
        // }

    }
}

.file-count {
    color: $color-text-light;
}

.drop-down-menu {
    z-index: 1;
    font-family: 'Raleway';
    font-style: normal;
    font-weight: 400;
    color: #8f8f8f;
    position: absolute;
    margin-top: 0;
    background: #fff;
    @include context-menu-box-shadow ;
    border-radius: 8px;
    border: 1px #cccccc solid;
    min-width: 130px;
    right: -20px;
    top:  30px;
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

            @media (max-width: 1024px) {
                padding-top: 10px;
                padding-bottom: 10px;
            }
            a {
                color :#4c4c4c;
            }
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

        a {
            color: $color-primary;
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
