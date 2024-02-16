<template>
    <div>
        <div class="item" :class="{active: data.id === selected.id}">
            <div class="item-content columns" style="padding-top: 4px;" :class="textClass" @click="$emit('select', {id: data.id, breadcrumb: breadcrumb})">

                <div class="icon-wrapper column is-1">
                    <span @click.stop="expand" @mouseover="iconHover = true" @mouseleave="iconHover = false" v-if="data.children.length" class="fa-stack">
                        <i style="font-size: 24px;" v-if="iconHover" class="fa fa-circle-thin fa-stack-2x"></i>
                        <i class="fa  menu-icon fa-stack-1x" :class="iconClass"></i>
                    </span>

                    <span v-if="!data.children.length" class="fa-stack">
                        <i class="fa fa-plus menu-icon disabled fa-stack-1x"></i>
                    </span>
                </div>

                <div class="text-wrapper column">
                    <a>{{ data.name }}</a>
                </div>

            </div>
        </div>

        <div v-if="expanded" class="level-menu" :class="{active: data.id === selected.id}">
            <menu-category @select="$emit('select', $event)" v-for="category in data.children" :key="category.id" :data="category" :selected="selected" :parent-breadcrumb="breadcrumb"></menu-category>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        data: {
            type: Object,
            required: true,
        },
        parentBreadcrumb: {
            type: Array,
            default: () => {return []},
        },
        selected: {
            type: Object,
            default: () => {return {}},
        }
    },
    computed: {
        textClass(){
            return 'subcategory-' + this.data.depth;
        },
        iconClass(){
            return !this.expanded ? 'fa-plus' : 'fa-minus';
        },
        textClamp(){
            return this.data.name.length > this.textLen ? this.data.name.substr(0,this.textLen) + '...' : this.data.name;
        }
    },
    data() {
        return {
            breadcrumb: this.parentBreadcrumb.concat([{id: this.data.id, name: this.data.name}]),
            expanded: false,
            iconHover: false,
            textLen: 10,
        }
    },
    beforeCreate: function() {
        // Vue.component('dashboard', require('./components/Dashboard.vue').default);
        this.$options.components.MenuCategory = require('./Menu-category.vue').default;
    },
    methods: {
        expand() {
            this.expanded = !this.expanded;
            this.$emit('expand');
        }
    }

}
</script>

<style lang="scss" scoped>
.fa-circle-thin{
    top:3px;
}
.level-menu{
    .fa-circle-thin{
        top: 5px;
    }
}
</style>
