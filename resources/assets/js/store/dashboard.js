import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
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
                Ps.update(state.listScrollbar);
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
            Ps.update(state.listScrollbar);
            // state.listScrollbar.scrollTop = state.listScrollbar.scrollHeight;
        },
        deselectFile:(state, data) => {
            if(data.outside !== true){
                state.selectedFiles.splice(state.selectedFiles.findIndex( item => item.type === 'file' && item.id === data.id ), 1);
            }
            Ps.update(state.listScrollbar);
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
            // Ps.update(state.listScrollbar);
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
        initListScrollbar: (state, obj) => {state.listScrollbar = obj;Ps.initialize(state.listScrollbar)},

    },
    actions: {
        resetStatesAction: ({commit}) => {commit('resetStatesMutation')},
    }
});