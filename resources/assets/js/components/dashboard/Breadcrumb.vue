<template>
    <div class="dashboard-breadcrumb">
        <span @click="$emit('select', {id: undefined, breadcrumb: []})" class="text">All categories</span>
        <span v-if="breadcrumb.length"> > </span>
        <span @click="$emit('select', {id: item.id, breadcrumb: breadcrumb.slice(0, index + 1)})" v-for="(item, index) in breadcrumb">

            <span @click="$emit('select', {id: item.id, breadcrumb: breadcrumb.slice(0, index + 1)})" class="text">{{item.name}}</span>
            <span v-if="index < (breadcrumb.length - 1)"> > </span>

        </span>
    </div>
</template>

<script>

export default {

    props: {
        breadcrumb: {
            type: Array,
            required: true,
        },
    },
}
</script>
