<template>
    <div class="item-list-container" :class="{'is-divider': data.type==='divider', 'is-closed': !expanded}">
        <div class="item-list" @click.prevent.stop="$store.commit('dashboard/updateSelectedItem', {value:!selectedItem, index:index})">

            <div class="arrow-down" @click.stop="expanded = !expanded">
                <i v-if="count > 1" class="fa fa-caret-right" :class="{expanded: expanded}"></i>
            </div>

            <div class="checkbox-group">
                <input v-model="selectedItem" :id="index" type="checkbox" class="styled-checkbox">
                <label :for="index"></label>
                <div class="list-name" :class="{hover: selectedItem}" >
                    <div ref="listName">{{ data.name }} <span v-if="data.type=='divider'" class="thin-name">({{data.type}})</span></div>
                </div>
            </div>


            <div class="col-icons">
                <a :href="data.path" target="_blank" @click.stop=""> <i class="fa fa-eye" v-if="data.type === 'file'"></i></a>
                <i class="fa fa-pencil edit-divider-icon" v-if="data.type === 'divider'" @click.stop="editDivider"></i>
                <i class="fa fa-trash" @click.stop="removeItem"></i>
            </div>
        </div>
        <transition name="fade-item" mode="out-in">
            <div v-if="expanded" class="item-pages">
                <div v-for="p in count" class="item-page">
                    <div class="item-content">
                        <i v-if="selectedPages.indexOf(p) > -1" class="fa fa-check check-mark" aria-hidden="true"></i>
                        <span class="page-title" :class="{'page-title--removed' : selectedPages.indexOf(p) === -1}">Page {{p}}</span>
                        <button v-if="selectedPages.indexOf(p) > -1" class="button-link button-link--delete" @click="toggleItem(p)">Remove</button>
                        <button v-if="selectedPages.indexOf(p) === -1" class="button-link" @click="toggleItem(p)">Add</button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>

import Clamp from 'clamp-js';

export default {
    props: {
        data: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            hover: false,
            count: 1,
            expanded: false,
        }
    },
    methods: {
        editDivider(){
            var send = Object.assign({}, this.data, {index:this.index});
            this.$store.commit('dashboard/syncDivData', send)
        },
        removeItem(){
            var send = Object.assign({}, this.data, {index:this.index});
            this.$store.commit('dashboard/removeItemConfirm', send)
        },
        getPages(){
            axios.get('/dashboard/page-count/' + this.data.id)
                .then( resp => {
                    this.count = resp.data.count;
                    const pages = [];
                    for (let i = 0; i < this.count; i++) {
                        pages[i] = i + 1;
                    }
                    this.$store.commit('dashboard/addPages', {index: this.data.id, pages: pages});
                })
                .catch( err => {
                    console.error(err)
                })
        },
        toggleItem(p) {
            const index = this.selectedPages.indexOf(p);

            if (index > -1) {
                this.$store.commit('dashboard/removePage', {index: this.data.id, page: p});
            } else {
                this.$store.commit('dashboard/addPage', {index: this.data.id, page: p});
            }
        }
    },
    mounted() {
        // Clamp(this.$refs.listName, {clamp: 1});
        if (this.data.type !=='divider') {
            this.getPages();
        }
    },
    computed:{
        selectedItem: {
            get () {
                return this.$store.state.dashboard.selectedFiles[this.index].selectedItem
            },
            set (value) {
                this.$store.commit('dashboard/updateSelectedItem', {value:value, index:this.index})
            }
        },
        selectedPages() {
            return this.$store.state.dashboard.selectedFiles[this.index].pages;
        }
    }
}
</script>

<style lang="scss" scoped>

@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";

.is-divider.divider-enter-active, .is-divider.divider-leave-active {
    transition: all 1s;
}
.is-divider.divider-enter, .is-divider.divider-leave-to /* .list-leave-active below version 2.1.8 */ {
    background-color: $color-dark;
    transition: all 1s;
    opacity: 0;
}
.item-list-container {
    backface-visibility: hidden;
    cursor: pointer;
    // height: 43px;

    &:hover {
        background: #ebedf0;
        &+.item-list-container{
            .item-list{
                border-top: 1px solid transparent;
            }
        }
        .item-list {

            .checkbox-group {
                .styled-checkbox {
                visibility: hidden;
                left: 20px;
                z-index: 9;
                width: 20px;
                cursor: pointer;
                height: 20px;

                    &:checked + label:before {
                        background: $color-primary;
                    }
                    & + label:before{
                        border: 1px solid $color-primary;
                    }

                }


            }
            .list-name{
                color: $color-primary
            }
            // .col-icons {
            //     visibility: visible;
            // }
        }
    }
    &.is-divider{
        .item-list{
            .list-name{
                font-weight: 600;
                color: $color-text-primary;
                .thin-name{
                    font-weight: 400;
                    color: #999
                }
            }
        }
    }

    .item-list {
        height: 43px;
        overflow: visible;

        // overflow: hidden;
        margin-left: 25px;
        margin-right: 35px;
        border-top: 1px solid #e1e3e6;
        padding-top: 9px;
        padding-bottom: 9px;
        @include flex;
        // justify-content: space-between;

        @media (max-width: 550px) {
            // padding-top: 0;
            // padding-bottom: 0;
            margin-left: 12px;
            margin-right: 12px;
        }

        &.hover-border{
            border-top: 1px solid transparent;
        }

        .arrow-down {
            @include flex;
            width: 20px;
            height: 100%;
            position: relative;
            i {
                margin-top: 4px;
                font-size: 18px;
                transform: rotate(0deg);
                @include transition(all .1s linear);
                &.expanded {
                    margin-top: 2px;
                    margin-left: -2px;
                    transform: rotate(90deg);
                }
            }
        }

        .checkbox-group {
            @include flex;
            @include flex-grow(1);
            // width: 10%;
            .styled-checkbox {
                & + label {
                    // margin-left: 20px;
                }
            }
        }

        .list-name {
            @include flex;
            @include flex-grow(1);
            overflow: hidden;
            font-weight: 400;
            color: $color-text-primary;
            width: 220px;

            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;

            line-height: 1.5;
            font-size: 16px;
            &.hover{
                color: $color-primary
            }
            p { overflow: hidden;}

        }

        .col-icons {
            width: 54px;
            @include flex;
            @include flex-direction-row;
            // visibility: hidden;
            cursor: pointer;
            color: $color-text-tertiary;
            i {
                min-width: 26px;
                @include flex;
                @include flex-grow(1);
                font-size: 16px;
                padding-top: 3px;
                color: $color-text-tertiary;
                &:hover {
                    color: $color-primary;
                }
            }
            i:first-child, i.edit-divider-icon {
                margin-right: 5px;
                margin-left: 5px;
            }
            @media(max-width: 550px) {
                display: none;
            }
        }
    }
    &:first-of-type{
        .item-list {
            border-top: none;
        }
    }

    .item-pages {
        background: #fff;
        width: 100%;

        .item-page {

            &:hover {
                background: #ebedf0;
            }

            .item-content {
                margin-left: 80px;
                margin-right: 35px;
                position: relative;

                @include flex;
                @include flex-direction-row;

                border-bottom: 1px solid #e1e3e6;

                @media (max-width: 606px) {
                    margin-left: 50px;
                }

                .check-mark {
                    position: absolute;
                    color: #404040;
                    left: -20px;
                    top: 18px;
                    @media (max-width: 1024px) {
                        top: 14px;
                    }
                }

                .checkbox-group {
                    @include flex;
                    @include flex-direction-column;
                    @include justify-content-center;
                }

                .page-title {
                    @include flex;
                    @include flex-direction-column;
                    @include justify-content-center;
                    @include flex-grow(1);
                    color: #404040;
                    &.page-title--removed {
                        color: #ccc;
                    }
                }

                .button-link {
                    @include box-sizing(border-box);
                    outline: none;
                    border: none;
                    background: none;
                    color: blue;
                    cursor: pointer;
                    height: 50px;

                    @media (max-width: 1024px) {
                        height: 42px;
                    }
                    &:hover {
                        text-decoration: underline;
                    }
                    &.button-link--delete {
                        color: #ff4733;
                    }
                }

            }
        }

    }


}


</style>
