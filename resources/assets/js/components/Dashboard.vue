<template>
    <div class="container">
        <div class="columns">
            <div id="dashboard-categories-menu" class="column is-2 dashboard-categories-menu">
                <div class="dashboard-action-buttons close" @click="toggleLeft">
                    <p><i class="fa fa-arrow-left" aria-hidden="true"></i> Close Categories </p>
                </div>
                <div class="title">Browse Categories</div>
                
                    <div class="dashboard-categories" @mouseenter.native="updateScrollbar('menuScrollbar')">

                        <div class="scroll-area" ref="menuScrollbar">

                            <div @click="reset" class="item">
                                <div class="item-content first">
                                    <a :class="{active: !selected.id}">All Categories</a>
                                </div>
                            </div>
                            <menu-category @expand="expandList" @select="select($event)" v-for="category in categories" :key="category.id" :data="category" :selected="selected" :parent-name="category.name"></menu-category>
                        </div>
                    </div>
                    <span class="dashboard-action-background" @click="toggleLeft">
                    </span>
            </div>
            <div class="column dashboard-main">
                <div v-show="width > 738" class="dashboard-search" :class="{focus : focus}">
                    <button type="button" @click="toggleLeft" class="button-base toggle-left">View Categories</button>
                    <span class="fa fa-search search-icon" @click="()=>{if(queryString)search()}"></span>
                    <span class="fa fa-times-circle clear-search" v-if="queryString" @click="queryString = ''; search()"></span>
                    <input @focus="focus = true" @blur="focus = false" v-model="queryString" :placeholder="searchPlaceholder" type="text" @keyup="search">
                    <button type="button" @click="$store.commit('dashboard/openModal','startOver')" class="button-base is-danger start-over">Start Over</button>
                    <button type="button" @click="toggleRight" class="button-base fill toggle-right">View Document</button>
                </div>

                <!-- <span v-if="width <= 738" class="search-small">
                    <div class="dashboard-search" :class="{focus : focus}">
                        <button type="button" @click="toggleLeft" class="button-base toggle-left">View Categories</button>
                        <button type="button" @click="toggleRight" class="button-base fill toggle-right">View Document</button>
                    </div>
                    <div class="dashboard-search" :class="{focus : focus}">
                        <span class="fa fa-search search-icon" @click="()=>{if(queryString)search()}"></span>
                        <span class="fa fa-times-circle clear-search" v-if="queryString" @click="queryString = ''; search()"></span>
                        <input @focus="focus = true" @blur="focus = false" v-model="queryString" :placeholder="searchPlaceholder" type="text" @keyup="search">
                    </div>
                </span> -->


                <div class="dashboard-files">

                    <breadcrumb v-if="width > 738" @select="select($event)" :breadcrumb="selected.breadcrumb"></breadcrumb>
                    <!-- @mouseenter="updateScrollbar('mainScrollbar')" -->
                    <div class="files-container" ref="mainScrollbar" @mouseenter.native="updateScrollbar('mainScrollbar')" infinite-wrapper>

                        <span v-show="width <= 738" class="search-small">
                            <div class="dashboard-search">
                                <button type="button"  @click="toggleLeft" class="button-base toggle-left">View Categories</button>
                                <button type="button"  @click="toggleRight" class="button-base fill toggle-right">View Document</button>
                            </div>
                            <div class="dashboard-search" :class="{focus : focus}">
                                <span class="fa fa-search search-icon" @click="()=>{if(queryString)search()}"></span>
                                <span class="fa fa-times-circle clear-search" v-if="queryString" @click="queryString = ''; search()"></span>
                                <input @focus="focus = true" @blur="focus = false" v-model="queryString" :placeholder="searchPlaceholder" type="text" @keyup="search">
                            </div>
                        </span>

                        <breadcrumb v-if="width <= 738" @select="select($event)" :breadcrumb="selected.breadcrumb"></breadcrumb>

                        <div v-if="width > 880" class="columns files-row large">
                            <div class="column files-column">

                                <file-item ref="mainfiles" v-if="index%3===0" v-for="(file, index) in files" :key="file.id" :ind="index" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                            <div class="column files-column">

                                <file-item ref="mainfiles" v-if="index%3===1" v-for="(file, index) in files" :key="file.id" :ind="index" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                            <div class="column files-column">

                                <file-item ref="mainfiles" v-if="index%3===2" v-for="(file, index) in files" :key="file.id" :ind="index" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                        </div>



                        <div  v-if="width <= 880 && width > 450" class="columns files-row medium">

                            <div class="column files-column">

                                <file-item ref="mainfiles" v-if="index%2===0" v-for="(file, index) in files" :key="file.id" :ind="index" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                            <div class="column files-column">

                                <file-item ref="mainfiles" v-if="index%2===1" v-for="(file, index) in files" :key="file.id" :ind="index" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                        </div>

                        <div  v-if="width <= 450" class="columns files-row small">

                            <div class="column files-column">

                                <file-item ref="mainfiles" v-for="(file, index) in files" :key="file.id" :data="file" @select="$store.commit('dashboard/selectFile', file)" @deselect="$store.commit('dashboard/deselectFile', file)"></file-item>

                            </div>

                        </div>


                        <infinite-loading @infinite="loadMore" ref="infiniteLoading" :distance="100">
                            <span slot="no-more">
                                No more results
                            </span>
                            <span slot="no-results">
                                No results found
                            </span>
                        </infinite-loading>

                    </div>

                </div>

            </div>
            <div id="dashboard-action" class="dashboard-action manage-upload">
                <div class="dashboard-action-buttons close" @click="toggleRight">
                    <!-- <button type="button" @click="toggleRight" class="button-base fill toggle-right">Close Document List</button> -->
                    <p> Close Document List <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                </div>
                <div class="dashboard-action-buttons">
                    <button type="button" class="button-base" :class="{borderless: st.modals.addHeader.exists}" @click="$store.commit('dashboard/openModal', 'addHeader')">{{headerState}} Header</button>
                    <button type="button" class="button-base" :class="{borderless: st.modals.addFooter.exists}" @click="$store.commit('dashboard/openModal', 'addFooter')">{{footerState}} Footer</button>
                    <button type="button" class="button-base" :class="{borderless: st.modals.addCover.exists}" @click="$store.commit('dashboard/openModal', 'addCover')">{{coverState}} Cover</button>
                </div>

                <div class="dashboard-action-list">

                    <div class="list-title">
                        <p>New Document<span>{{filesCount}}<span>Files Added</span></span> </p>
                    </div>
                    {{expandedList}}
                    <div class="dashboard-list" ref="listScrollbar">
                    <draggable v-model="st.selectedFiles" :options="{draggable: '.is-closed'}">
                        <transition-group name="divider">
                            <file-list-item :pages="selectedPages[index]" :data="item" :index="index" @remove="$store.commit('dashboard/removeItemConfirm')" v-for="(item, index) in selectedFiles" :key="index"></file-list-item>
                        </transition-group>
                    </draggable>
                    <div v-if="st.selectedFiles.length === 0" class="empty-filelist-msg" :class="{'is-danger': emptyState === true}" >Please select the files you want to merge into a new document</div>
                    </div>

                    <div class="dashboard-list-action">
                        <button class="button-base simple flex-button" @click="$store.commit('dashboard/openModal', 'addDivider')">Insert Divider</button>
                        <button class="button-base simple flex-button button-base__upload-file" @click="$store.commit('dashboard/openModal', 'uploadFileDashboard')"><i class="pdfglue-icon upload-file"></i></button>
                        <!-- <button v-if="width > 550" class="button-base simple flex-button" @click="$store.commit('dashboard/removeItemsConfirm')">Remove</button> -->
                        <button class="button-base simple flex-button button-base__remove-selected" @click="$store.commit('dashboard/removeItemsConfirm')"><i class="fa fa-trash-o"></i></button>
                        <button class="button-base simple button-icon flex-button" @click="moveDown"><i class="fa fa-arrow-down"></i></button>
                        <button class="button-base simple button-icon flex-button" @click="moveUp"><i class="fa fa-arrow-up"></i></button>
                    </div>
                    <div class="list-title insert-page-number">
                        <div class="field">
                            <span>
                                <input type="checkbox" v-model="removeNumbering" class="styled-checkbox" id="rmv" ref="rmbMe"></input>
                                <label for="rmv" @mouseenter="hoverRmb = true" @mouseleave="hoverRmb = false"></label>
                                <span class="rmb" :class="{'active' : hoverRmb}" @click="$refs.rmbMe.click()">Apply page numbering</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="dashboard-list-main-action">
                    <button type="button" class="button-base fill">
                        <transition name="fade" mode="out-in">
                            <div class="save-button" v-if="documentState === false" key="editing" @click="mergePdf">Create New Document</div>
                            <div class="ok-button"  v-if="documentState === true" key="saved">Creating Document</div>
                        </transition>
                    </button>
                </div>
                <span class="dashboard-action-background" @click="toggleRight">
                </span>
            </div>
        </div>
        <transition-group name="fade" mode="out-in">
            <modal-add-header v-if="modals.addHeader.show" @delete="$store.commit('dashboard/openModal','deleteHeader')" @close="$store.commit('dashboard/closeModal','addHeader')" :key="'addHeader'"></modal-add-header>
            <modal-add-footer v-if="modals.addFooter.show" @delete="$store.commit('dashboard/openModal','deleteFooter')" @close="$store.commit('dashboard/closeModal','addFooter')" :key="'addFooter'"></modal-add-footer>
            <modal-add-cover v-if="modals.addCover.show" @delete="$store.commit('dashboard/openModal','deleteCover')" @close="$store.commit('dashboard/closeModal','addCover')" :key="'addCover'"></modal-add-cover>
            <modal-add-divider v-if="modals.addDivider.show" @close="$store.commit('dashboard/closeModal','addDivider')" :key="'addDiv'"></modal-add-divider>
            <modal-upload-file-dashboard v-if="modals.uploadFileDashboard.show" @close="$store.commit('dashboard/closeModal','uploadFileDashboard')" :width="this.width" :key="'uploadFile'"></modal-upload-file-dashboard>
            <modal-document-created v-if="modals.documentCreated" @save="$store.commit('dashboard/closeModal')" @close="$store.commit('dashboard/closeModal','documentCreated')" :key="'docCreate'"></modal-document-created>
            <modal-delete-header v-if="modals.deleteHeader" @save="$store.commit('dashboard/deleteHFC', 'addHeader')" @close="$store.commit('dashboard/closeModal','deleteHeader')" :key="'delheader'"></modal-delete-header>
            <modal-delete-footer v-if="modals.deleteFooter" @save="$store.commit('dashboard/deleteHFC', 'addFooter')" @close="$store.commit('dashboard/closeModal','deleteFooter')" :key="'delFooter'"></modal-delete-footer>
            <modal-delete-cover v-if="modals.deleteCover" @save="$store.commit('dashboard/deleteHFC', 'addCover')" @close="$store.commit('dashboard/closeModal','deleteCover')" :key="'delCover'"></modal-delete-cover>
            <modal-remove-item v-if="modals.removeItem" @save="removeItem" @close="$store.commit('dashboard/closeModal','removeItem')" :key="'removeItem'"></modal-remove-item>
            <modal-remove-items v-if="modals.removeItems" @save="removeItems" @close="$store.commit('dashboard/closeModal','removeItems')" :key="'removeItems'"></modal-remove-items>
            <modal-start-over v-if="modals.startOver" @save="startOver" @close="$store.commit('dashboard/closeModal','startOver')" :key="'startOver'"></modal-start-over>
        </transition-group>
    </div>
</template>
<script>
    import Breadcrumb from './dashboard/Breadcrumb.vue'
    import InfiniteLoading from 'vue-infinite-loading'
    import MenuCategory from './dashboard/Menu-category.vue'
    import PerfectScrollbar from 'perfect-scrollbar';

    // import 'perfect-scrollbar/css/perfect-scrollbar.css';
    import FileItem from './dashboard/File-item.vue'
    import FileListItem from './dashboard/File-list-item.vue'
    import Draggable from 'vuedraggable'
    import Vue from 'vue'


    import ModalAddHeader from './modals/Modal-addHeader.vue';
    import ModalAddFooter from './modals/Modal-addFooter.vue';
    import ModalAddCover from './modals/Modal-addCover.vue';
    import ModalAddDivider from './modals/Modal-addDivider.vue';
    import ModalUploadFileDashboard from './modals/Modal-uploadFileDashboard.vue';
    import ModalDocumentCreated from './modals/Modal-documentCreated.vue';
    import ModalDeleteHeader from './modals/Modal-deleteHeader.vue';
    import ModalDeleteFooter from './modals/Modal-deleteFooter.vue';
    import ModalDeleteCover from './modals/Modal-deleteCover.vue';
    import ModalStartOver from './modals/Modal-startOver.vue';
    import ModalRemoveItem from './modals/Modal-removeItem.vue';
    import ModalRemoveItems from './modals/Modal-removeItems.vue';
   
    window.Store.registerModule('dashboard', {
        namespaced: true,
        state: {
            listScrollbar: undefined,
            modals: {
                addHeader: {show:false, exists:false, content:"", verb:"Add"},
                addFooter: {show:false, exists:false, content:"", verb:"Add"},
                addCover: {show:false, exists:false, content:{project:"",projectType:"",customer:"",job:""}, verb:"Add"},
                addDivider: {show:false, exists:false, data:{name:"",content:""}},
                uploadFileDashboard: {show: false, exists: false, data:{}},
                myAccount: false,
                changePass: false,
                documentCreated: false,
                deleteHeader: false,
                deleteFooter: false,
                deleteCover: false,
                startOver: false,
                removeItem: false,
                removeItems: false
            },
            removeNumbering: true,
            successState: false,
            failState: false,
            itemSelected: {},
            filePath: "",
            selectedFiles: [],
            selectedPages: [],

        },
        mutations: {
            addPages: (state, value) => {
                for (let i = 0; i < state.selectedFiles.length; i++) {
                    if (state.selectedFiles[i].id === value.index) {
                        Vue.set(state.selectedFiles[i], 'pages', value.pages);
                        break;
                    }
                }
                // state.selectedFiles[value.index].pages = value.pages;
            },
            addPage: (state, value) => {
                for (let i = 0; i < state.selectedFiles.length; i++) {
                    if (state.selectedFiles[i].id === value.index) {
                        state.selectedFiles[i].pages.push(value.page);
                        break;
                    }
                }
            },
            removePage: (state, value) => {

                for (let i = 0; i < state.selectedFiles.length; i++) {
                    if (state.selectedFiles[i].id === value.index) {

                        const pages = state.selectedFiles[i].pages;

                        for (let j = 0; j < state.selectedFiles[i].pages.length; j++) {
                            if (state.selectedFiles[i].pages[j] === value.page) {
                                state.selectedFiles[i].pages.splice(j, 1);
                                break;
                            }
                        }
                        break;
                    }
                }



            },
            filePathMutation: (state, value) => {state.filePath = value},
            updateSelectedFiles: (state, value) => {state.selectedFiles = value},
            updateSelectedItem: (state, obj) => { Vue.set(state.selectedFiles[obj.index], 'selectedItem', obj.value); },
            openModal: (state, value) => {
                if(typeof state.modals[value].show !== 'undefined'){
                    state.modals[value].show = true;
                }else{
                    state.modals[value] = true;
                }
            },
            closeModal: (state, value) => {
                if(typeof value !== 'undefined'){
                    if(typeof state.modals[value].show !== 'undefined'){
                        state.modals[value].show = false;
                    }else{
                        state.modals[value] = false;
                    }
                }else{
                    for(var sample in state.modals){
                        if(typeof state.modals[sample].show !== 'undefined'){
                            state.modals[sample].show = false;
                        }else{
                            state.modals[sample] = false;
                        }
                    }
                }
            },
            saveHFC:(state, modal)=>{
                state.modals[modal.name].content = modal.data;
                state.modals[modal.name].show = false;
                state.modals[modal.name].exists = true;
                state.successState = false;
            },
            deleteHFC: (state, modal) =>{
                state.successState = false;
                if(typeof state.modals[modal].content === "object"){
                    for(var sample in state.modals[modal].content){
                        state.modals[modal].content[sample] = ""
                    }
                }else{
                    state.modals[modal].content = "";
                }
                state.modals[modal].exists = false;
                window.Store.commit('dashboard/closeModal');
            },
            syncDivData: (state, data) => {
                state.modals.addDivider.data = data;
                state.modals.addDivider.show = true;
            },
            insertDivider: (state, data) => {
                if(!data.content.length){
                    return false;
                }else{
                    if(typeof data.type !== 'undefined'){
                        state.selectedFiles[data.index].name = data.name;
                        state.selectedFiles[data.index].content = data.content;
                    }else{
                        var dividerObj = {};
                        dividerObj.name = data.name;
                        dividerObj.content = data.content;
                        state.selectedFiles.push(Object.assign({}, dividerObj, {type: 'divider'}));
                    }
                    this.Ps.update(state.listScrollbar);
                    state.modals.addDivider.data = {name:"", content:""};
                    state.modals.addDivider.show = false;
                    state.successState = false;
                }
            },
            failStateMutation: (state, data) => {state.failState = data },
            successStateMutation: (state, data) => { state.successState = data },
            resetStatesMutation: (state) => { state.successState = false, state.failState = false;},
            selectFile:(state, data) => {
                if(state.selectedFiles.length>0){
                    for(var i=0;i<state.selectedFiles.length;i++){
                        if(state.selectedFiles[i].id === data.id){
                            return;
                        }
                    }
                    state.selectedFiles.push(Object.assign({}, data, {type: 'file'}));
                }else{
                    state.selectedFiles.push(Object.assign({}, data, {type: 'file'}));
                }
                this.Ps.update(state.listScrollbar);
                // state.listScrollbar.scrollTop = state.listScrollbar.scrollHeight;
            },
            deselectFile:(state, data) => {
                if(data.outside !== true){
                    state.selectedFiles.splice(state.selectedFiles.findIndex( item => item.type === 'file' && item.id === data.id ), 1);
                }
                this.Ps.update(state.listScrollbar);
            },
            removeItemConfirm: (state, data) => {
                state.itemSelected = data;
                state.modals.removeItem = true;
            },
            removeItem: (state, data) => {
                var that = this;
                if(!data.id){
                    state.selectedFiles.splice(data.index,1);
                }else{
                    var indexul = that.$refs.mainfiles.findIndex(item => item.data.id === data.id );
                    if(typeof that.$refs.mainfiles[indexul] !== "undefined"){
                        that.$refs.mainfiles[indexul].deSelected();
                    }else{
                        state.selectedFiles.splice(data.index, 1);
                    }

                }
                this.Ps.update(state.listScrollbar);
                state.modals.removeItem = false;
            },
            removeItemsConfirm: (state, data)=>{
                for(var i=0; i < state.selectedFiles.length; i++){
                    if(state.selectedFiles[i].selectedItem === true){
                        state.modals.removeItems = true;
                    }
                }
            },
            removeItems:(state)=>{
                var newSearch = [];
                var that = this;
                for(var i=0; i < state.selectedFiles.length; i++){
                    if(state.selectedFiles[i].selectedItem !== true){
                        newSearch.push(state.selectedFiles[i])
                    }
                }
                for(var i=0; i < state.selectedFiles.length; i++){
                    if(state.selectedFiles[i].type === 'file' && state.selectedFiles[i].selectedItem === true){
                        var indexul = that.$refs.mainfiles.findIndex(item => item.data.id === state.selectedFiles[i].id );
                        if(typeof that.$refs.mainfiles[indexul]  !== "undefined"){
                            that.$refs.mainfiles[indexul].deSelected(true);
                        }
                    }
                }
                state.selectedFiles = newSearch;
                state.modals.removeItems = false;
            },
            // initListScrollbar: (state, obj) => {state.listScrollbar = obj;Ps.initialize(state.listScrollbar)},

        },
        actions: {
            resetStatesAction: ({commit}) => {commit('resetStatesMutation')},
        }
    });
    
    export default {
        mounted() {
            this.Ps = new PerfectScrollbar(this.$refs.mainScrollbar, {
                suppressScrollX: true,
            });
            
            this.MPs = new PerfectScrollbar(this.$refs.menuScrollbar);
            this.load();
            this.width = document.body.clientWidth;
            window.addEventListener('resize', (e) => {
                if (this.width <= 880 && document.body.clientWidth > 880 || this.width > 880 && document.body.clientWidth <= 880) {
                    this.width = document.body.clientWidth;
                        this.refresh();
                        var that = this;
                        // Vue.nextTick(that.refresh)

                } else {
                    this.width = document.body.clientWidth;
                }
            })

        },
        components:{
            Breadcrumb,
            MenuCategory,
            InfiniteLoading,
            FileItem,
            FileListItem,
            Draggable,
            ModalAddHeader,
            ModalAddFooter,
            ModalAddCover,
            ModalUploadFileDashboard,
            ModalDocumentCreated,
            ModalDeleteHeader,
            ModalDeleteFooter,
            ModalDeleteCover,
            ModalAddDivider,
            ModalStartOver,
            ModalRemoveItem,
            ModalRemoveItems
        },
        data() {
            return{
                focus: false,
                categories: [],
                files: [],
                selected: {
                    id: undefined,
                    breadcrumb: [],
                },
                queryString: '',
                searchTimeout: undefined,
                page: 0,
                percent: 0,
                loadingState: false,
                documentState: false,
                emptyState: false,
                hoverRmb: false,
                width: 0,
                selectedPages: [],
            }
        },
        computed: {
            st(){
                return this.$store.state.dashboard;
            },
            searchPlaceholder() {
                return 'Search in ' + (this.selected.id ? this.selected.breadcrumb[this.selected.breadcrumb.length - 1].name : 'All Categories') + '...';
            },
            filesCount() {
                return this.$store.state.dashboard.selectedFiles.filter((item) => {return item.type === 'file';}).length;
            },
            modals(){
                return this.$store.state.dashboard.modals;
            },
            headerState(){
                return !this.$store.state.dashboard.modals.addHeader.exists ? 'Add' : 'Edit';
            },
            footerState(){
                return !this.$store.state.dashboard.modals.addFooter.exists ? 'Add' : 'Edit';
            },
            coverState(){
                return !this.$store.state.dashboard.modals.addCover.exists ? 'Add' : 'Edit';
            },
            selectedFiles(){
                return this.$store.state.dashboard.selectedFiles
            },
            removeNumbering:{
                get(){
                    return !this.$store.state.dashboard.removeNumbering;
                },
                set(value){
                    this.$store.state.dashboard.removeNumbering = !value;
                }
            },
            expandedList() {
                return this.$store.state.dashboard.expanded;
            }

        },
        methods:{
            load() {
                axios.get('/dashboard/categories').then( ({data}) => {
                    this.categories = this.categories.concat(data);
                    this.$refs.menuScrollbar.scrollTop = 0;
                    this.MPs.update(this.$refs.menuScrollbar);
                    
                    }).catch(({data}) => console.error(data));
            },
            expandList() {
                 this.MPs.update(this.$refs.menuScrollbar);
            },
            removeItem(data){
                if(!data.id){
                    this.$store.state.dashboard.selectedFiles.splice(data.index,1);
                }else{
                    var indexul = this.$refs.mainfiles.findIndex(item => item.data.id === data.id );
                    if(typeof this.$refs.mainfiles[indexul] !== "undefined"){
                        this.$refs.mainfiles[indexul].deSelected();
                    }else{
                        this.$store.state.dashboard.selectedFiles.splice(data.index,1);
                    }

                }
                this.Ps.update(this.$refs.listScrollbar);
                this.$store.state.dashboard.modals.removeItem = false;
            },
            removeItems(data){
                var newSearch = [];
                var that = this;
                for(var i=0; i < this.$store.state.dashboard.selectedFiles.length; i++){
                    if(this.$store.state.dashboard.selectedFiles[i].selectedItem !== true){
                        newSearch.push(this.$store.state.dashboard.selectedFiles[i])
                    }
                }
                for(var i=0; i < this.$store.state.dashboard.selectedFiles.length; i++){
                    if(this.$store.state.dashboard.selectedFiles[i].type === 'file' && this.$store.state.dashboard.selectedFiles[i].selectedItem === true){
                        var indexul = that.$refs.mainfiles.findIndex(item => item.data.id === this.$store.state.dashboard.selectedFiles[i].id );
                        if(typeof that.$refs.mainfiles[indexul]  !== "undefined"){
                            that.$refs.mainfiles[indexul].deSelected(true);
                        }
                    }
                }
                // this.$store.commit('dashboard/updateSelectedFiles', newSearch);
                this.modals.removeItems = false;
            },
            moveDown(){
                var newSearch = [];
                var oldL = [];
                var newL = [];
                var leng = this.selectedFiles.length;
                if(this.selectedFiles[leng-1].selectedItem === true){
                    return false;
                }
                for(var i=this.selectedFiles.length-2; i>=0; i--){
                    if(this.selectedFiles[i].selectedItem === true){
                        oldL.push(i);
                        newL.push(i+1);
                    }
                }
                this.swap(oldL, newL);
            },
            moveUp(){
                var newSearch = [];
                var oldL = [];
                var newL = [];
                var leng = this.selectedFiles.length;
                if(this.selectedFiles[0].selectedItem === true){
                    return false;
                }
                for(var i=1; i < this.selectedFiles.length; i++){
                    if(this.selectedFiles[i].selectedItem === true){
                        oldL.push(i);
                        newL.push(i-1);
                    }
                }
                this.swap(oldL, newL);
            },
            swap(oldl, newl) {
                var len = oldl.length;
                for (var i = 0; i < len; i++) {
                    var oldi = oldl[i];
                    var newi = newl[i];
                    var temp = this.$store.state.dashboard.selectedFiles[oldi];
                    this.$set(this.$store.state.dashboard.selectedFiles, oldi, this.$store.state.dashboard.selectedFiles[newi])
                    this.$set(this.$store.state.dashboard.selectedFiles, newi, temp)
                }
            },
            mergePdf(){
                if(!this.$store.state.dashboard.selectedFiles.length){
                    this.emptyState = true;
                    var that = this;
                    setTimeout(() => {that.emptyState = false},200)
                } else{

                    let totalPages = 0;
                    let dividersCount = 0;

                    for (let k = 0; k < this.$store.state.dashboard.selectedFiles.length; k++) {

                        if (this.$store.state.dashboard.selectedFiles[k].type === 'file' && this.$store.state.dashboard.selectedFiles[k].pages.length > 0) {
                            totalPages++;
                            break;

                        } else if (this.$store.state.dashboard.selectedFiles[k].type === 'divider') {
                            dividersCount++;
                            break;
                        }
                    }

                    if (dividersCount === 0 && totalPages === 0) {

                        this.emptyState = true;
                        var that = this;
                        setTimeout(() => {that.emptyState = false},200)
                        return;

                    }

                    this.documentState = true;
                    this.loadingState = true;
                    this.percent = 0;
                    var token = document.head.querySelector('meta[name="csrf-token"]');
                    var postBody = {
                        _token: token.content,
                        removeNumbering: this.$store.state.dashboard.removeNumbering,
                    }
                    if(this.$store.state.dashboard.removeNumbering){
                        postBody.removeNumbering = this.$store.state.dashboard.removeNumbering;
                    }
                    if(this.$store.state.dashboard.modals.addHeader.content.length){
                        postBody.header = this.$store.state.dashboard.modals.addHeader.content;
                    }
                    if(this.$store.state.dashboard.modals.addCover.content.project.length || this.$store.state.dashboard.modals.addCover.content.customer.length || this.$store.state.dashboard.modals.addCover.content.projectType.length){
                        postBody.cover = this.$store.state.dashboard.modals.addCover.content;
                    }
                    if(this.$store.state.dashboard.modals.addFooter.content.length){
                        postBody.footer = this.$store.state.dashboard.modals.addFooter.content;
                    }
                    postBody.items = this.$store.state.dashboard.selectedFiles.map(
                        (item) => {
                            return (item.type === "divider")?{type:item.type, text:item.content}:{type:item.type, id:item.id, pages: item.pages ? item.pages : []}
                        }
                    );
                    var that = this;
                    axios.post('/dashboard/create', postBody,{
                        onUploadProgress: ev => {
                            that.percent = parseInt(ev.loaded * 100 / ev.total);
                        }
                    }).then(function ({data}){
                        that.loadingState = false;
                        that.percent = 0;
                        that.documentState = false;
                        that.$store.commit('dashboard/filePathMutation', data);
                        that.$store.commit('dashboard/openModal','documentCreated');
                    }).catch((error) => {
                        console.error(error);
                    });

                }

            },
            startOver(){
                this.$store.commit('dashboard/updateSelectedFiles', []);
                for(var i=0; i < this.$refs.mainfiles.length; i++){
                    this.$refs.mainfiles[i].selected = false;
                }
                this.$store.commit('dashboard/deleteHFC', 'addHeader');
                this.$store.commit('dashboard/deleteHFC', 'addFooter');
                this.$store.commit('dashboard/deleteHFC', 'addCover');
            },
            search() {
                if (this.searchTimeout) {clearTimeout(this.searchTimeout)}
                this.searchTimeout = setTimeout( () => {  this.refresh()}, 200);
            },
            select(val) {
                
                this.selected.id = val.id;
                this.selected.breadcrumb = val.breadcrumb;
                this.refresh();
                this.$refs.menuScrollbar.scrollTop = 0;
                this.MPs.update(this.$refs.menuScrollbar);
            },
            updateScrollbar(val){
                this.Ps.update(this.$refs[val]);
            },
            refresh() {
                this.page = 0;
                this.request((data) => {
                    
                     // this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
                    for(var i=0; i<this.$store.state.dashboard.selectedFiles.length; i++){
                        var indexul = data.data.findIndex(item => item.id === this.$store.state.dashboard.selectedFiles[i].id );
                        if(indexul >= 0){
                            data.data[indexul].selected = true;
                        }
                    }
                    this.files = data.data;
                    this.page = data.current_page;
                    this.$refs.mainScrollbar.scrollTop = 0;
                    this.Ps.update(this.$refs.mainScrollbar);
                });
            },
            resetSelectedCheckbox() {
                // $refs.mainfiles
                var that = this;
                for(var i=0; i<this.$store.state.dashboard.selectedFiles.length; i++){
                    var indexul = that.$refs.mainfiles.findIndex(item => item.data.id === this.$store.state.dashboard.selectedFiles[i].id );
                    if(indexul >= 0){
                        that.$refs.mainfiles[indexul].resizeSelected()
                    }
                }
                this.Ps.update(this.$refs.mainScrollbar);
            },
            reset() {
                this.selected = {
                    id: undefined,
                    breadcrumb: []
                };
                this.refresh();
            },
            loadMore($state) {
                this.page++;
                this.request((data) => {

                    if (!data.data.length) {
                        $state.complete();
                    }
                    for(var i=0; i<this.$store.state.dashboard.selectedFiles.length; i++){
                        var indexul = data.data.findIndex(item => item.id === this.$store.state.dashboard.selectedFiles[i].id );
                        if(indexul >= 0){
                            data.data[indexul].selected = true;
                        }
                    }
                    this.files = this.files.concat(data.data);
                    $state.loaded();
                    this.page = data.current_page;
                    this.Ps.update(this.$refs.mainScrollbar);

                });
            },
            request(callback) {
                axios.get('/dashboard/files', {
                    params: {
                        q: this.queryString,
                        cat: this.selected.id,
                        page: this.page,
                    }
                }).then(({data}) => {
                    callback(data);
                }).catch((error) => {
                    console.error(error);
                });
            },

            toggleRight() {

                const panel = document.getElementById('dashboard-action');
                const ind = panel.className.indexOf('active');
                if (ind > -1) {
                    panel.className = panel.className.slice(0, ind - 1);
                } else {
                    panel.className += ' active';
                }

            },

            toggleLeft() {

                const panel = document.getElementById('dashboard-categories-menu');
                const ind = panel.className.indexOf('active');
                if (ind > -1) {
                    panel.className = panel.className.slice(0, ind - 1);
                } else {
                    panel.className += ' active';
                }

            },
        }

    }
</script>


<style lang="scss">
@import "resources/assets/sass/variables";
.toggle-right {
    display: none;
    margin-left: 15px;
    margin-right: 3px;
    @media(max-width: 1545px) {
        display: block;
    }
}

.toggle-left {
    display: none;
    @media(max-width: 1024px) {
        display: block;
        margin-right: 15px;
    }
}
.columns{
    display: flex
}
.ps__scrollbar-y-rail{
    margin-right: 3px;
}
.dashboard-action .dashboard-action-list .list-title{
    &.insert-page-number{
        margin: 12px 40px;
        text-align: center;
        .field{
            &>span{
                &:hover{
                    color: red;
                    label:before{
                        border: 1px solid $color-primary;
                    }
                }
            }

        }
        .styled-checkbox {
            &+label{
                font-size: 14px;
                float: none;
                width: 25px;
                &:before{
                    height: 16px;
                    width: 16px;
                    margin-top: 0;
                    margin-right: 5px;
                }
                &:after{
                    top: 8px;
                }
            }
        }

        .rmb{
            color: $color-text-primary;
            padding: 0;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            line-height: 1.2;
            letter-spacing: 0;
            // border-bottom: 2px solid transparent;
            &:hover, &.active{
                color: $color-primary;
                // border-bottom: 2px solid $color-dark;
            }
        }

    }

}

</style>

