<template>
    <div @mouseenter="openpreview(fileId)" 
                    @mouseleave="hidepreview(fileId)">
        <div class="file columns" @mouseenter="hover = true" @mouseleave="hover = false">
            <div class="column is-1" >

                <input v-model="selected" :class="{hover : hover || selected}" class="styled-checkbox" :id="'input-' + fileId" type="checkbox">
                <label :for="'input-' + fileId"  ></label>
                <a :href="data.path" target="_blank">
                    <i class="fa fa-eye" :class="{hover : hover || selected}"></i>
                </a>
            
            </div>
            <div class="column is-file-details">

                <div ref="fileTitle" class="file-title" :class="{hover : hover || selected}" @click="selected =! selected">{{ data.name }}</div>

                <div class="file-description" ref="scrollContent">

                    <p> {{ description }} </p>

                </div>

            </div>
           
        </div>
        <div :id="'iframe-' + fileId" class="iframe-preview file columns hide">
            <iframe  :src="data.path" ></iframe>
        </div>  
    </div>
</template>

<script>

import Ps from 'vue-perfect-scrollbar';
import Clamp from 'clamp-js';
import { mapActions } from 'vuex';

export default {
    props: {
        data: {
            type: Object,
            required: true,
        },
        ind:{}
    },
    data() {
        return {
            hover: false,
            selected: this.data.selected || false,
            outside: false
        }
    },
    methods:{
        openpreview(id)
        {
            console.log(id);
            const el = document.querySelector('#iframe-'+id);
            el.classList.remove("hide");
        },
        hidepreview(id)
        {
            const el = document.querySelector('#iframe-'+id);
            el.classList.add("hide");
      
        },  
        deSelected(x = false){
            this.data.outside = false;
            if(x !== false){
                this.data.outside = true;
            }
            this.selected = false;
        },
        resizeSelected(){
            this.data.outside = true;
            this.selected = true;
        }
    },
    mounted(){
        Clamp(this.$refs.fileTitle, 1);
        // Ps.initialize(this.$refs.scrollContent);
    },
    computed: {
        fileId() {
            return 'file-' + this.data.id;
        },
        description(){
            return this.data.description || "No description added."
        }
    },
    watch: {
        selected(val){
            if (val) {
                this.$emit('select');
            } else {
                if(this.data.outside !== true){
                    this.$emit('deselect');
                }
            }
            this.data.outside = false;
        }
    }
}
</script>

<style lang="scss" scoped>
    .hide
    {
        display:none;
    }
    .iframe-preview {
        position:absolute;
        z-index: 999;
        top: 0;

        /* width: 180px; */
    }
    
</style>
