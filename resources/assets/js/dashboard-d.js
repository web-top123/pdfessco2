require('./bootstrap');

import Breadcrumb from './components/dashboard/Breadcrumb.vue'
import FileItem from './components/dashboard/File-item.vue'
import InfiniteLoading from 'vue-infinite-loading'
import MenuCategory from './components/dashboard/Menu-category.vue'
import Ps from 'vue-perfect-scrollbar'

import ModalTest from './components/modals/Modal-test.vue'
import Clamp from 'clamp-js'
import FileListItem from './components/dashboard/File-list-item.vue'
import Draggable from 'vuedraggable'
import ModalMyAccount from './components/modals/Modal-myAccount.vue'
import ModalChangePass from './components/modals/Modal-changePass.vue'
import ModalAddHeader from './components/modals/Modal-addHeader.vue'
import ModalAddFooter from './components/modals/Modal-addFooter.vue'
import ModalAddCover from './components/modals/Modal-addCover.vue'
import ModalAddDivider from './components/modals/Modal-addDivider.vue'
import ModalEditDivider from './components/modals/Modal-editDivider.vue'
import ModalDocumentCreated from './components/modals/Modal-documentCreated.vue'
import ModalDeleteHeader from './components/modals/Modal-deleteHeader.vue'
import ModalDeleteFooter from './components/modals/Modal-deleteFooter.vue'
import ModalDeleteCover from './components/modals/Modal-deleteCover.vue'
import ModalStartOver from './components/modals/Modal-startOver.vue'
import ModalRemoveItem from './components/modals/Modal-removeItem.vue'
import ModalRemoveItems from './components/modals/Modal-removeItems.vue'

const navbar = require('./navbar')()

Vue.component('example', require('./components/Example.vue'))

const app = new Vue({
    mixins: [navbar],
    el: '#app', 
    components: {
        Breadcrumb,
        Draggable,
        FileItem,
        FileListItem,
        InfiniteLoading,
        MenuCategory,
        ModalTest,
        ModalMyAccount,
        ModalChangePass,
        ModalAddHeader,
        ModalAddFooter,
        ModalAddCover,
        ModalDocumentCreated,
        ModalDeleteHeader,
        ModalDeleteFooter,
        ModalDeleteCover,
        ModalAddDivider,
        ModalEditDivider,
        ModalStartOver,
        ModalRemoveItem,
        ModalRemoveItems
    },
    data: {
        categories: categories,
        files: files.data,
        selected: {
            id: undefined,
            breadcrumb: [],
        },
        queryString: '',
        searchTimeout: undefined,
        page: files.current_page,
        focus: false,
        selectedFiles: [],
        /*states for vuex*/
        headerExists: false,
        headerContent: "",
        successHeaderState: false,
        footerExists: false,
        footerContent: "",
        successFooterState: false,
        coverExists: false,
        coverContent: "",
        successCoverState: false,
        /** states vuex */
        filePath: "",
        dividerData: {name:"", content:""},
        dividerEditContent:{},
        itemSelected:{},
        modals: {
            addHeader: false,
            addFooter: false,
            addCover: false,
            addDivider: false,
            editDivider: false,
            myAccount: false,
            changePass: false,
            documentCreated: false,
            deleteHeader: false,
            deleteFooter: false,
            deleteCover: false,
            startOver: false,
            removeItem: false,
            removeItems: false
        }
    },
    computed: {
        searchPlaceholder() {
            return 'Search in ' + (this.selected.id ? this.selected.breadcrumb[this.selected.breadcrumb.length - 1].name : 'All Categories') + '...';
        },
        filesCount() {
            return this.selectedFiles.filter((item) => {return item.type === 'file';}).length;
        },
        headerState(){
            return !this.headerExists ? 'Add' : 'Edit';
        },
        footerState(){
            return !this.footerExists ? 'Add' : 'Edit';
        },
        coverState(){
            return !this.coverExists ? 'Add' : 'Edit';
        }
    },
    mounted() {
        Ps.initialize(this.$refs.mainScrollbar, {
            suppressScrollX: true,
        });
        Ps.initialize(this.$refs.menuScrollbar);
        Ps.initialize(this.$refs.listScrollbar);

    },
    methods: {
        select(val) {
            this.selected.id = val.id;
            this.selected.breadcrumb = val.breadcrumb;
            this.refresh();
            this.$refs.menuScrollbar.scrollTop = 0;
            Ps.update(this.$refs.menuScrollbar);
        },
        refresh() {
            this.page = 0;
            this.request((data) => {
                this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
                for(var i=0; i<this.selectedFiles.length; i++){
                    var indexul = data.data.findIndex(item => item.id === this.selectedFiles[i].id );
                    if(indexul >= 0){
                        data.data[indexul].selected = true;
                    }
                }
                this.files = data.data;
                this.page = data.current_page;
                this.$refs.mainScrollbar.scrollTop = 0;
                
                Ps.update(this.$refs.mainScrollbar);
            });
        },
        reset() {
            this.selected = {
                id: undefined,
                breadcrumb: []
            };
            this.refresh();
        },
        loadMore() {
            this.page++;
            this.request((data) => {

                if (!data.data.length) {

                    return this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
                }
                for(var i=0; i<this.selectedFiles.length; i++){
                    var indexul = data.data.findIndex(item => item.id === this.selectedFiles[i].id );
                    if(indexul >= 0){
                        data.data[indexul].selected = true;
                    }
                }
                data.data.forEach((item) => {
                    this.files.push(item);
                });
                this.$refs.infiniteLoading.$emit('$InfiniteLoading:loaded');
                this.page = data.current_page;
                Ps.update(this.$refs.mainScrollbar);

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
        expandList() {
            Ps.update(this.$refs.menuScrollbar);
        },
        selectFile(data) {
            if(this.selectedFiles.length>0){
                for(var i=0;i<this.selectedFiles.length;i++){
                    if(this.selectedFiles[i].id === data.id){
                        return;
                    }
                }
                this.selectedFiles.push(Object.assign({}, data, {type: 'file'}));
            }else{

                this.selectedFiles.push(Object.assign({}, data, {type: 'file'}));
            }

            Ps.update(this.$refs.listScrollbar);
            this.$refs.listScrollbar.scrollTop = this.$refs.listScrollbar.scrollHeight;
        },
        deselectFile(data) {
            if(data.outside !== true){
                this.selectedFiles.splice(this.selectedFiles.findIndex( item => item.type === 'file' && item.id === data.id ), 1);
            }
            Ps.update(this.$refs.listScrollbar);
        },
        removeItemConfirm(data){
            this.itemSelected = data;
            this.modals.removeItem = true;
        },
        removeItem(data){
            if(!data.id){
                this.selectedFiles.splice(data.index,1);
            }else{
                var indexul = this.$refs.mainfiles.findIndex(item => item.data.id === data.id );
                if(typeof this.$refs.mainfiles[indexul] !== "undefined"){
                    this.$refs.mainfiles[indexul].deSelected();
                }else{
                    this.selectedFiles.splice(data.index,1);
                }
                
            }
            Ps.update(this.$refs.listScrollbar);
            this.modals.removeItem = false;
        },
        removeItemsConfirm(){
            for(var i=0; i < this.selectedFiles.length; i++){
                if(this.selectedFiles[i].selectedItem === true){
                    this.modals.removeItems = true;
                }
            }
        },
        removeItems(data){
            var newSearch = [];
            var that = this;
            for(var i=0; i < this.selectedFiles.length; i++){
                if(this.selectedFiles[i].selectedItem !== true){
                    newSearch.push(this.selectedFiles[i])
                }
            }
            for(var i=0; i < this.selectedFiles.length; i++){
                if(this.selectedFiles[i].type === 'file' && this.selectedFiles[i].selectedItem === true){
                    var indexul = that.$refs.mainfiles.findIndex(item => item.data.id === this.selectedFiles[i].id );
                    if(typeof that.$refs.mainfiles[indexul]  !== "undefined"){
                        that.$refs.mainfiles[indexul].deSelected(true);
                    }
                }
            }
            this.selectedFiles = newSearch;
            this.modals.removeItems = false;
        },
        search() {
            if (this.searchTimeout) {clearTimeout(this.searchTimeout)}
            this.searchTimeout = setTimeout( () => { this.refresh()}, 200);
        },
        closeModals(modalName = false){
            if(!modalName){
               for(var sample in this.modals){
                   this.modals[sample] = false;
               }
            }else{
                this.modals[modalName] = false;
            }
        },
        openModal(modalName){
            this.modals[modalName] = true;
        },
        saveHeader(data){
            if(!data.length){
                this.deleteHeader();
            }else{
                this.headerContent = data;
                this.headerExists = true;
                this.modals.addHeader = false;
            }
        },
        deleteHeader(){
            this.successHeaderState = false;
            this.headerContent = "";
            this.headerExists = false;
            this.closeModals();
        },
        saveFooter(data){
            if(!data.length){
                this.deleteFooter();
            }else{
                this.footerContent = data;
                this.footerExists = true;
                this.modals.addFooter = false;
            }
        },
        deleteFooter(){
            this.successFooterState = false;
            this.footerContent = "";
            this.footerExists = false;
            this.closeModals();
        },
        saveCover(data){
            if(!data.length){
                this.deleteCover();
            }else{
                this.coverContent = data;
                this.coverExists = true;
                this.modals.addCover = false;
            }
        },
        deleteCover(){
            this.successCoverState = false;
            this.coverContent = "";
            this.coverExists = false;
            this.closeModals();
        },
        insertDivider(data){
            if(!data.content.length){
                return false;
            }else{
                var dividerObj = {};
                dividerObj.name = data.name;
                dividerObj.content = data.content;
                this.selectedFiles.push(Object.assign({}, dividerObj, {type: 'divider'}));
                Ps.update(this.$refs.listScrollbar);
                this.dividerData = {name:"", content:""};
                this.modals.addDivider = false;
            }
        },
        openEditModal(data){
            this.dividerEditContent = data;
            this.modals.editDivider = true;
        },
        editDivider(data){
            this.dividerEditContent = data;
            this.selectedFiles[data.index].name = data.name;
            this.selectedFiles[data.index].content = data.content;
            this.modals.editDivider = false;
        },
        mergePdf(){
            if(!this.selectedFiles.length){
                return false;
            }else{
                var token = document.head.querySelector('meta[name="csrf-token"]');
                var postBody = {
                    _token: token.content
                }
                if(this.headerContent.length){
                    postBody.header = this.headerContent
                }
                if(this.coverContent.length){
                    postBody.cover = this.coverContent
                }
                if(this.footerContent.length){
                    postBody.footer = this.footerContent
                }
                postBody.items = this.selectedFiles.map(
                    (item) => {
                        return (item.type === "divider")?{type:item.type, text:item.name}:{type:item.type, id:item.id}
                    }
                );
                var that = this;
                axios.post('/dashboard/create', postBody).then(function ({data}){
                    that.filePath = data;
                    that.modals.documentCreated = true;
                }).catch((error) => {
                    console.error(error);
                });

            }
           
        },
        moveDown(){
            var newSearch = [];
            var oldL = [];
            var newL = [];
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
        
                var temp = this.selectedFiles[oldi]; 
                this.$set(this.selectedFiles, oldi, this.selectedFiles[newi])
                this.$set(this.selectedFiles, newi, temp)
            }
        },
        startOver(){
            this.selectedFiles = [];
            for(var i=0; i < this.$refs.mainfiles.length; i++){
                this.$refs.mainfiles[i].selected = false;
            }
            this.deleteHeader();
            this.deleteFooter();
            this.deleteCover();
        }
    },
});
