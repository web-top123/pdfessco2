<template>
<div class="container">

    <div class="main-display">

        <div class="action-bar" :class="{scrolled: scrollShadow}" v-if="width > 550">

            <div class="search">
                <span class="fa fa-search search-icon" @click="searchItems(search)"></span>
                <span class="fa fa-times-circle clear-search" v-if="search" @click="search = ''; searchItems(search)"></span>
                <input class="text" v-model="search" type="text" placeholder="Search for files..." @keyup="searchItems(search)">
            </div>

            <div class="buttons">
                <button v-if="width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'create')">Create Category</button>
                <button v-if="width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'create')"><i class="fa fa-folder-o"></i></button>


                <button v-if="hasSelected && width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'move')">Move To</button>
                <button v-if="hasFilesSelected && width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'copy')">Copy To</button>
                <button v-if="!hasSelected && width > 800" class="button-base">Move To</button>
                <button v-if="!hasFilesSelected && width > 800" class="button-base">Copy To</button>
                <button v-if="hasSelected && width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'move')"><i class="fa fa-external-link"></i></button>
                <button v-if="hasFilesSelected && width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'copy')"><i class="fa fa-clone"></i></button>
                <button v-if="!hasSelected && width <= 800" class="button-base"><i class="fa fa-external-link"></i></button>
                <button v-if="!hasFilesSelected && width <= 800" class="button-base"><i class="fa fa-clone"></i></button>


                <button v-if="hasSelected && width > 800" class="button-base button-delete" @click="$store.commit('manageFiles/openModal', 'delete')"> Delete </button>
                <button v-if="!hasSelected && width > 800" class="button-base button-delete"> Delete</button>
                <button v-if="hasSelected && width <= 800" class="button-base button-delete" @click="$store.commit('manageFiles/openModal', 'delete')"> <i class="fa fa-trash-o"></i> </button>
                <button v-if="!hasSelected && width <= 800" class="button-base button-delete"> <i class="fa fa-trash-o"></i></button>


                <button v-if="width > 800" type="button" @click="toggleRight" class="button-base fill toggle-right">File Upload</button>
                <button v-if="width <= 800" type="button" @click="toggleRight" class="button-base fill toggle-right"><i class="pdfglue-icon upload-file"></i></button>

            </div>

        </div>

        <div class="manage" ref="mainScrollbar" infinite-wrapper>

            <div class="action-bar" v-if="width <= 550">

                <div class="search">
                    <span class="fa fa-search search-icon" @click="searchItems(search)"></span>
                    <span class="fa fa-times-circle clear-search" v-if="search" @click="search = ''; searchItems(search)"></span>
                    <input class="text" v-model="search" type="text" placeholder="Search for files..." @keyup="searchItems(search)">
                </div>

                <div class="buttons">
                    <button v-if="width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'create')">Create Category</button>
                    <button v-if="width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'create')"><i class="fa fa-folder-o"></i></button>


                    <button v-if="hasSelected && width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'move')">Move To</button>
                    <button v-if="hasFilesSelected && width > 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'copy')">Copy To</button>
                    <button v-if="!hasSelected && width > 800" class="button-base">Move To</button>
                    <button v-if="!hasFilesSelected && width > 800" class="button-base">Copy To</button>
                    <button v-if="hasSelected && width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'move')"><i class="fa fa-external-link" style="margin-left:5px"></i></button>
                    <button v-if="!hasSelected && width <= 800" class="button-base"><i class="fa fa-external-link" style="margin-left:5px"></i></button>
                    <button v-if="hasFilesSelected && width <= 800" class="button-base" @click="$store.commit('manageFiles/openModal', 'copy')"><i class="fa fa-clone" style="margin-left:5px"></i></button>
                    <button v-if="!hasFilesSelected && width <= 800" class="button-base"><i class="fa fa-clone" style="margin-left:5px"></i></button>


                    <button v-if="hasSelected && width > 800" class="button-base button-delete" @click="$store.commit('manageFiles/openModal', 'delete')"> Delete </button>
                    <button v-if="!hasSelected && width > 800" class="button-base button-delete"> Delete</button>
                    <button v-if="hasSelected && width <= 800" class="button-base button-delete" @click="$store.commit('manageFiles/openModal', 'delete')"> <i class="fa fa-trash-o"></i> </button>
                    <button v-if="!hasSelected && width <= 800" class="button-base button-delete"> <i class="fa fa-trash-o"></i></button>


                    <button v-if="width > 800" type="button" @click="toggleRight" class="button-base fill toggle-right">File Upload</button>
                    <button v-if="width <= 800" type="button" @click="toggleRight" class="button-base fill toggle-right"><i class="pdfglue-icon upload-file"></i></button>
                </div>

            </div>

            <span v-if="!search">
                <div class="columns" v-if="width > 500">
                    <div class="column">
                        <div class="card" v-if="item.hide !== true && index%2 === 0" v-for="(item, index) in categories" :key="item.id">
                            <category-item @expanded="expandCatEv" :data="item" :top-level="true" :parent-last="true" :last="true" :last-in-array="true"></category-item>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card" v-if="item.hide !== true && index%2 !== 0" v-for="(item, index) in categories" :key="item.id">
                            <category-item @expanded="expandCatEv" :data="item" :top-level="true" :parent-last="true" :last="true" :last-in-array="true"></category-item>
                        </div>
                    </div>
                </div>

                <div class="columns" v-if="width <= 500">
                    <div class="column">
                        <div class="card" v-if="item.hide !== true" v-for="(item, index) in categories" :key="item.id">
                            <category-item @expanded="expandCatEv" :data="item" :top-level="true" :parent-last="true" :last="true" :last-in-array="true"></category-item>
                        </div>
                    </div>
                </div>
            </span>

            <span v-show="search">

                <div class="columns" v-if="width > 500">
                    <div class="column">
                        <div class="card" v-if="index%2 === 0" v-for="(item, index) in searchList" :key="item.id">
                            <search-item :data="item"></search-item>
                        </div>
                    </div>
                    <div class="column">
                        <div class="card" v-if="index%2 !== 0" v-for="(item, index) in searchList" :key="item.id">
                            <search-item :data="item"></search-item>
                        </div>
                    </div>
                </div>
                <div class="columns" v-if="width <= 500">
                    <div class="column">
                        <div class="card" v-for="(item, index) in searchList" :key="item.id">
                            <search-item :data="item"></search-item>
                        </div>
                    </div>
                </div>

                <infinite-loading v-if="search" class="infinite-loading" @infinite="loadMoreSearch" ref="infiniteLoading" :distance="100">
                    <span slot="no-more">
                        No more results
                    </span>
                    <span slot="no-results">
                        No results found
                    </span>
                </infinite-loading>

            </span>


        </div>

    </div>

    <div class="menu" id="dashboard-action">
        <div class="dashboard-action-buttons close" @click="toggleRight">
            <p> Close File Upload <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>
        <p class="upload-title">File Upload</p>
        <div class="form-group">
            <label>Title</label>
            <input type="text"  :class="{error: errors.title, 'disabled-title':upload.defaultTitle}" placeholder="Add Title..." v-model="upload.title" @keyup="errors.title = false">
            <span class="default__title--container">
                <input v-model="upload.defaultTitle" id="default_title" type="checkbox" class="styled-checkbox">
                <label for="default_title" style="padding-left:0"></label>Use the default file name as a title
            </span>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea rows="5"  :class="{error: errors.description}" placeholder="Add Description..." v-model="upload.description" @keyup="errors.description = false"></textarea>
        </div>

        <div class="form-group category-group">
            <label>Category</label>
            <span :class="{'error-m': errors.category}" @click="errors.category = false">
            <multiselect v-model="upload.category" :show-labels="false" :max-height="350" placeholder="Select a category..." label="name" :options="list">
                <template slot="option" scope="props">
                    <div :style="{'margin-left': (props.option.depth * 20) + 'px', 'font-weight' : 700 - (props.option.depth * 100)}"></option>
                        {{props.option.name}}
                    </div>
                </template>
                <span slot="noResult"> No results </span>
            </multiselect>
            </span>
        </div>
        <div class="form-group upload-input-group">
            <label class="upload-file">Upload File</label>
            <div class="upload-area" :class="{drag : upload.drag, error: errors.file}" @dragenter="upload.drag = true" @dragleave="upload.drag = false" @drop="upload.drag = false">
                <span class="wrapper" v-show="!upload.loading">
                    <div v-if="!upload.file" class="no-upload" >
                        <p>Drag and Drop Files Here</p>
                        <p>or</p>
                        <button type="button" class="button-base simple">Click to Choose File</button>
                    </div>
                    <div v-if="upload.file" class="has-upload">{{ upload.file.name }}</div>
                    <form ref="fileReset"><input name="file" type="file" accept="application/pdf" @change="processFile($event)" ref="fileInput"></form>
                </span>
                <span v-show="upload.loading">
                    <p class="has-upload">Uploading {{ upload.percentShow }}%</p>
                </span>
            </div>

            <button type="button" class="upload-area-button button-base simple" @click="$refs.fileInput.click();">{{!upload.file ? 'Click to Choose File' : upload.file.name}}</button>

        </div>

        <button type="button" class="button-base fill main-button upload-main-button" @click="uploadPdf">Upload PDF File</button>

        <span class="dashboard-action-background" @click="toggleRight">
        </span>
    </div>

    <transition name="fade" mode="out-in">
        <modal-delete name="delete" v-if="modals.delete"></modal-delete>
        <modal-edit-category name="edit-category" v-if="modals['edit-category']"></modal-edit-category>
        <modal-edit-pdf name="edit-pdf" v-if="modals['edit-pdf']"></modal-edit-pdf>
        <modal-create-category name="create" v-if="modals['create']"></modal-create-category>
        <modal-move name="move" v-if="modals['move']"></modal-move>
        <modal-copy name="copy" v-if="modals['copy']"></modal-copy>
        <modal-upload name="upload" v-if="modals['upload']"></modal-upload>
        <modal-explode name="explode" v-if="modals['explode']"></modal-explode>
    </transition>
</div>
</template>

<script>
/**
 * Vendor libraries
 */
import Multiselect from 'vue-multiselect';
import PerfectScrollbar from 'perfect-scrollbar';

import InfiniteLoading from 'vue-infinite-loading';
import {
    mapState,
    mapActions
} from 'vuex';



/**
 * Components
 */
// import CategoryCard from './admin/Category-card.vue';
import CategoryItem from './admin/Category-item.vue';
import SearchItem from './admin/Search-item.vue';

// import ModalDelete from './modals/Modal-delete.vue';
// import ModalEditCategory from './modals/Modal-edit-category.vue';

// const ModalDelete = () => System.import('./modals/Modal-delete.vue');
// const ModalEditCategory = () => System.import('./modals/Modal-edit-category.vue');
// const ModalEditPdf = () => System.import('./modals/Modal-edit-pdf.vue');
// const ModalCreateCategory = () => System.import('./modals/Modal-create-category.vue');
// const ModalMove = () => System.import('./modals/Modal-move-files-to-category.vue');
// const ModalUpload = () => System.import('./modals/Modal-upload-to-category.vue');

import ModalDelete from './modals/Modal-delete.vue';
import ModalEditCategory from './modals/Modal-edit-category.vue';
import ModalEditPdf from './modals/Modal-edit-pdf.vue';
import ModalCreateCategory from './modals/Modal-create-category.vue';
import ModalMove from './modals/Modal-move-files-to-category.vue';
import ModalCopy from './modals/Modal-copy-files-to-category.vue';
import ModalUpload from './modals/Modal-upload-to-category.vue';
import ModalExplode from './modals/Modal-explode.vue';


const naturalCompare = (a, b) => {
    var ax = [],
        bx = [];

    a.name.replace(/(\d+)|(\D+)/g, function(_, $1, $2) {
        ax.push([$1 || Infinity, $2 || ""])
    });
    b.name.replace(/(\d+)|(\D+)/g, function(_, $1, $2) {
        bx.push([$1 || Infinity, $2 || ""])
    });

    while (ax.length && bx.length) {
        var an = ax.shift();
        var bn = bx.shift();
        var nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1]);
        if (nn) return nn;
    }

    return ax.length - bx.length;
}

const expandCategory = (arr, data) => {

    return arr.map( category => {

        if (category.children) {

            category.children = expandCategory(category.children, data);

        }

        if (category.id === data.id) {
            category = data;
        }

        return category;

    });

}

const moveCategory = (arr, data) => {

    if (!data.parent_id) {
        arr.push(data);
        return arr.sort(naturalCompare);
    }

    return arr.map( category => {

        //REMOVE
        if (category.children) {
            category.children = moveCategory(category.children.filter( item => item.id !== data.id), data);
        }


        //ADD
        if (category.id === data.parent_id) {
            category.children.push(data);
            category.children.sort(naturalCompare);
        }

        return category;
    });

}

const updateCategory = (arr, data) =>{

    const result = arr.map((category) => {
        if (category.children) {
            category.children = updateCategory(category.children, data);
        }

        if (category.id === parseInt(data.parent_id)) {
            if (category.files) {
                category.files = category.files.concat(data.files);
                category.files.sort(naturalCompare);

            }
            category.files_count += data.files.length;
        }

        return category;

    });
    result.sort(naturalCompare);

    return result;
}

const deleteCategory = (arr, data) => {

    return arr.filter( category => {

        if (category.id === data.id) {

            return false;

        }

        if (category.children) {
            category.children = deleteCategory(category.children, data);
        }

        if (category.id === data.parent_id) {
            if (category.children_count) {
                category.children_count++;
            }
        }

        return true;
    })

}


const moveRecursive = (arr, data) => {

    return arr.map( category => {

        if (category.children) {
            category.children = moveRecursive(category.children, data);
        }
        if (category.id === data.parent_id) {


            if (category.children) {

                data.categories.forEach(item => { if (!category.children.find( child => child.id === item.id)) category.children.push(item) } )
                category.children.sort(naturalCompare)
            }

            if (category.files) {
                data.files.forEach(item => { if (!category.files.find( file => file.id === item.id)) category.files.push(item) } )
                category.files.sort(naturalCompare)
            }


            category.children_count += data.categories.length;
            category.files_count += data.files.length;
        }

        return category
    })


    // return arr.map((category) => {
    //
    //     if (category.children) {
    //         category.children = moveRecursive(category.children, del, add);
    //     }
    //
    //     //REMOVE
    //     if (category.files) {
    //         category.files = category.files.filter(file => {
    //             const find = del.findIndex(x => x.id === file.id);
    //             if (find > -1) {
    //                 del.splice(find, 1);
    //                 return false;
    //             }
    //
    //             return true;
    //         });
    //     }
    //
    //     //ADD
    //     for (let i = 0; i < add.length; i++) {
    //
    //         if (add[i].category_id === category.id) {
    //             if (category.files) {
    //                 category.files.push(add[i]);
    //                 add.splice(i--, 1);
    //             }
    //         }
    //
    //     }
    //
    //     category.files.sort(naturalCompare);
    //
    //     return category;
    // });

}

const addRecursive = (arr, data) => {

    if (!data.isFile && !data.parent_id) {
        arr.push(data);
        // arr.sort(naturalCompare);

        return arr;
    }


    const result = arr.map((category) => {

        if (category.children) {

            category.children = addRecursive(category.children, data);
        }


        if (data.isFile) {


            if (category.id === parseInt(data.category_id)) {
                if (category.files) {
                    category.files.push(data);
                    category.files.sort(naturalCompare);

                }
                category.files_count++;
            }

        } else {

            if (category.id === data.parent_id) {
                if (category.children) {
                    category.children.push(data);
                    category.children.sort(naturalCompare);
                }
                category.children_count++;
            }

        }

        return category;

    });

    result.sort(naturalCompare);

    return result;

}

const deleteRecursive = (arr, data) => {

    return arr.filter((category) => {

        if (category.children) {
            category.children = deleteRecursive(category.children, data);
            category.children_count = category.children.length;
        }

        if (category.files) {
            category.files = category.files.filter((file) => {
                return data.files.indexOf(file.id) < 0;
            });

            category.files_count = category.files.length;
        }

        return data.categories.indexOf(category.id) < 0;
    });

}

const editRecursive = (arr, data) => {

    const result = arr.map((category) => {

        if (category.children) {

            category.children = editRecursive(category.children, data);

        }

        if (data.isFile) {

            if (category.files) {

                category.files = category.files.map((file) => {

                    if (file.id === data.id) {

                        file.name = data.name;
                        file.description = data.description;
                    }
                    return file;
                });

                category.files.sort(naturalCompare);
            }

        } else {

            if (category.children) {
                category.children.sort(naturalCompare);
            }

            if (category.id === data.id) {
                category.name = data.name;
            }
        }

        return category;

    });

    if (!data.isFile) {

        result.sort(naturalCompare);

    }

    return result;
}

/**
 * Store Module configuration
 */
window.Store.registerModule('manageFiles', {
    namespaced: true,
    state: {
        categories: [],
        selected: {
            categories: [],
            files: [],
        },
        list: [],
        search: {
            result: [],
            selected: {
                categories: [],
                files: [],
            },
            categories: false,
            query: '',
            timeout: 0,
            limit: false,
        },
        modals: {
            data: undefined,
            delete: false,
            'edit-category': false,
            'edit-pdf': false,
            create: false,
            move: false,
            upload: false,
            explode: false,
            copy: false,
        },
        columns: { left: 0, right: 0},
    },
    mutations: {
        categories: (state, value) => {

            state.categories = state.categories.concat(value);
            // state.columns.left = 0;
            // state.columns.right = 0;
            // console.log('start');
            // state.categories.forEach( (item) => {
            //     if (state.columns.left <= state.columns.right) {
            //
            //         item.left = true;
            //         state.columns.left += item.total;
            //         console.log('left', state.columns, item.name);
            //     } else {
            //
            //         item.right = true;
            //         state.columns.right += item.total;
            //         console.log('right', state.columns, item.name);
            //
            //     }
            // });
            // console.log(window.msn);
            // window.msn.reloadItems();
        },
        updateCategories: (state, value) =>{

        },
        newCategories: (state, value) => {
            state.categories = value;
        },
        newSearch: (state, value) => state.search.result = value,
        selectSearch: (state, value) => state.search.selected.files.push(value),
        unselectSearch: (state, value) => state.search.selected.files = state.search.selected.files.filter( item => item !== value),

        list: (state, value) => state.list = value,
        search: (state, value) => {
            state.search.query = value;
            state.search.result = [];
            state.search.limit = false;
        },
        // clearSearch: (state) => state.search.results = [],
        searchResult: (state, value) => {
            state.search.result = state.search.result.concat(value)
            if (value.length < 20) {
                state.search.limit = true;
            }
        },
        openModal: (state, value) => {

            if (typeof value === 'string') {
                state.modals[value] = true;
                state.modals.data = undefined;

            } else {

                state.modals[value.name] = true;
                state.modals.data = value.data;

            }

        },
        closeModal: (state, value) => state.modals[value] = false,
        clearSelected: (state) => state.selected = {
            categories: [],
            files: []
        },
    },
    actions: {
        categories: ({
            commit,
            state,
            getters
        }) => {

            // const search = state.search.query.slice();

            axios.get('/admin/categories/cards', {
                    // params: {
                    //     offset: getters.offset,
                    //     q: state.search.query
                    // }
                })
                .then(({
                    data
                }) => {

                    // if (state.search.query && search !== state.search.query) {
                    //     return
                    // }

                    // data.forEach( (item) => {
                    //     if (state.columns.left <= state.columns.right) {
                    //
                    //         item.left = true;
                    //         state.columns.left += item.total;
                    //
                    //     } else {
                    //
                    //         item.right = true;
                    //         state.columns.right += item.total;
                    //
                    //     }
                    // });

                    // if (state.search.query) {
                    //     commit('searchResult', data);
                    // } else {
                    //     if (!search)
                    // }

                    commit('categories', data);
                }).catch((data) => console.error(data));

        },

        searchFiles: ({commit, state, getters}) => {

            const search = state.search.query.slice();
            // console.log('search', search);
            return axios.get('/admin/search', {params: {q: state.search.query, page: getters.offset}})
                .then(({data}) => {
                    // console.log('recieve', state.search.query, search);
                    if (state.search.query && search !== state.search.query) {
                        // console.log('a')
                        return
                    }
                    commit('searchResult', data)

                }).catch(err => console.error(err))
        },
        list: ({
            commit
        }) => axios.get('/admin/categories/list').then(({
            data
        }) => commit('list', data)).catch(({
            data
        }) => console.error(data)),

        delete: ({
            commit,
            dispatch,
            state
        }, data) => {
            dispatch('list');
            if (state.search.query) {
                commit('newSearch', state.search.result.filter( item => !data.files.find(id => id === item.id)))
            }
            commit('newCategories', deleteRecursive(state.categories.slice(), data));
        },

        edit: ({
            commit,
            dispatch,
            state
        }, data) => {
            dispatch('list');
            if (data.isFile && state.search.query) {
                commit('newSearch', state.search.result.map( item => item.id === data.id ? data : item ) )
            }
            commit('newCategories', editRecursive(state.categories.slice(), data));
        },

        add: ({
            commit,
            dispatch,
            state
        }, data) => {
            dispatch('list');
            commit('newCategories', addRecursive(state.categories.slice(), data));
        },
        move: ({
            commit,
            dispatch,
            state
        }, data) => {
            if (state.search.query) {
                commit('newSearch', state.search.result.map( item => {

                    const fItem = data.files.find( it => it.id === item.id);
                    return fItem ? fItem : item;
                }) )
            }
            commit('newCategories', moveRecursive(deleteRecursive(state.categories.slice(), {files: data.files.map(item => item.id), categories: data.categories.map(item => item.id)}), data))
        },
        upload: ({
            commit,
            dispatch,
            state
        }, data) => {
            if (state.search.query) {
                if (data.name.indexOf(state.search.query) > -1) {
                    const tArr = state.search.result.slice();
                    tArr.push(data)
                    tArr.sort(naturalCompare);
                    commit('newSearch', tArr);
                }
            }
            commit('newCategories', addRecursive(state.categories.slice(), data));
        },
        copy: ({
            commit,
            dispatch,
            state
        }, data) => {

            if (state.search.query) {
                const tArr = state.search.result.slice();
                var sArr = data.files.map( file => {
                    if(file.name.toLowerCase().indexOf(state.search.query) > -1){
                        tArr.push(file)
                        tArr.sort(naturalCompare);
                    }
                    return tArr;
                },state.search);

                commit('newSearch', sArr[0]);
            }
            commit('newCategories', updateCategory(state.categories.slice(),data));
        },
        moveCategory: ({ commit, state}, data) => commit('newCategories', moveCategory(state.categories.slice(), data )),
        loadCategory: ({commit, state}, data) => axios.get('/admin/categories/' + data.id).then( response => commit('newCategories', expandCategory(state.categories.slice(), response.data )) ),
        deleteCategory: ({commit, state}, data) => commit('newCategories', deleteCategory(state.categories.slice(), data)),
    },
    getters: {
        createList: (state) => state.list.map((item) => {
            return Object.assign({
                '$isDisabled': item.depth > 3
            }, item)
        }),
        offset: (state) => parseInt(Math.ceil(state.search.result.length / 20)) + 1,
    }
});

/**
 * Component configuration
 */
export default {
    components: {
        Multiselect,
        InfiniteLoading,

        CategoryItem,
        SearchItem,

        ModalDelete,
        ModalEditCategory,
        ModalEditPdf,
        ModalCreateCategory,
        ModalMove,
        ModalCopy,
        ModalUpload,
        ModalExplode,
    },

    data() {
        return {

            upload: {
                description: '',
                drag: false,
                file: undefined,
                category: undefined,
                title: '',
                loading: false,
                percent: 0,
                percentShow: 0,
                defaultTitle: true
            },
            search: '',
            scrollShadow: false,
            errors: {
                category: false,
                file: false,
                title: false,
                description: false,
            },
            width: 0,
        }
    },
    mounted() {
        this.$store.dispatch('manageFiles/list');
      
        this.Ps = new PerfectScrollbar(this.$refs.mainScrollbar, {
                suppressScrollX: true,
        });
            
        // Ps.initialize(this.$refs.mainScrollbar, {
        //     suppressScrollX: true
        // });

        // console.log(this.$refs.mainScrollbar.style['background'] = 'red');

        this.$refs.mainScrollbar.addEventListener('ps-scroll-y', () => {
            if (this.$refs.mainScrollbar.scrollTop > 0) {
                this.scrollShadow = true;
            }
        });

        this.$refs.mainScrollbar.addEventListener('ps-y-reach-start', () => {
            this.scrollShadow = false;
        });

        this.width = document.body.clientWidth;
        window.addEventListener('resize', (e) => {
            this.width = document.body.clientWidth;
        })

        // window.msn = new Masonry (document.querySelector('.column'), {
        //     itemSelector: '.card',
        // });
        //
        //



        this.loadMore();
        // this.$refs.mainScrollbar.

    },

    computed: {
        categories() {
            return this.$store.state.manageFiles.categories;
        },
        list() {
            return this.$store.state.manageFiles.list;
        },
        hasSelected() {
            return this.$store.state.manageFiles.search.query ? this.$store.state.manageFiles.search.selected.categories.length || this.$store.state.manageFiles.search.selected.files.length : this.$store.state.manageFiles.selected.categories.length || this.$store.state.manageFiles.selected.files.length;
        },
        hasFilesSelected(){
            return this.$store.state.manageFiles.search.query ? this.$store.state.manageFiles.search.selected.files.length && !this.$store.state.manageFiles.search.selected.categories.length : this.$store.state.manageFiles.selected.files.length && !this.$store.state.manageFiles.selected.categories.length;
        },
        modals() {
            return this.$store.state.manageFiles.modals;
        },
        searchList() {
            return this.$store.state.manageFiles.search.result;
        },
        deleteText() {
            return this.width > 750 ? 'Delete' : '<i class="fa fa-trash" ></i>'
        }
    },

    methods: {
        loadMore(ev, search) {
            this.$store.dispatch('manageFiles/categories', ev)
            .then( () => {
                // this.scrollTop();
            })
        },
        loadMoreSearch(ev) {
            // if (this.search !== this.$store.state.manageFiles.search.query) {
            //     this.$store.commit('manageFiles/search', this.search)
            // }

            if (!this.$store.state.manageFiles.search.limit) {
                this.$store.dispatch('manageFiles/searchFiles')
                .then( () => {
                    ev.loaded();
                    if (this.$store.state.manageFiles.search.limit) {
                        // this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
                        ev.complete();
                    }
                })
            } else {
                ev.complete();
            }
        },
        searchItems(search) {
                if (this.search !== this.$store.state.manageFiles.search.query) {
                    this.$store.commit('manageFiles/search', this.search)
                    this.scrollTop();
                    // console.log(this.$refs.infiniteLoading.isLoading)
                    if (this.$refs.infiniteLoading && !this.$refs.infiniteLoading.isLoading) {
                        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
                    }
                }

            // if (search !== this.$store.state.manageFiles.search.query) {
            //     this.scrollTop();
            //     this.$store.commit('manageFiles/search', search)
            //     if (search) {
            //         this.$store.dispatch('manageFiles/searchFiles');
            //         // this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
            //     }
            // }

        },
        scrollTop() {
            this.$refs.mainScrollbar.scrollTop = 0;
            this.Ps.update(this.$refs.mainScrollbar);
        },
        uploadPdf() {

            let hasErrors = false;

            if (!this.upload.category || !this.upload.category.id) {
                this.errors.category = true;
                hasErrors = true;
            }

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

            const data = new FormData();
            data.append('title', this.upload.title);
            data.append('description', this.upload.description);
            data.append('category', this.upload.category.id);
            data.append('file', this.upload.file);

            this.upload.percent = 0;
            this.upload.loading = true;

            return axios.post('/admin/file', data, {
                    onUploadProgress: ev => {
                        this.upload.percent = parseInt(ev.loaded * 100 / ev.total);
                    }
                })
                .then(({
                    data
                }) => {
                    this.upload.loading = false;
                    this.upload.percent = 0;
                    this.upload.percentShow = 0;

                    this.$store.dispatch('manageFiles/upload', Object.assign({
                        isFile: true
                    }, data));
                    this.upload.description = '';
                    this.upload.category = undefined;
                    this.upload.title = '';
                    this.upload.file = undefined;
                    this.$refs.fileReset.reset();
                    this.clearErrors();
                })
                .catch((error) => {
                    this.errors.title = error.response.data.errors.title ? true : false;
                    this.errors.file = error.response.data.errors.file ? true : false;
                    this.errors.description = error.response.data.errors.description ? true : false;

                    this.upload.loading = false;
                });

        },
        processFile(event) {
            this.upload.file = event.target.files[0];
            this.errors.file = false;
            if (!this.upload.title) {
                this.upload.title = this.upload.file.name;
            }
        },
        clearErrors() {
            this.errrors = {
                title: false,
                file: false,
                description: false,
                category: false,
            };
        },
        expandCatEv() {
            // var _this = this;
            // setTimeout(function () {

                 this.Ps.update(this.$refs.mainScrollbar);
            // }, 1000)
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
    // categories: () => this.$redrawVueMasonry(),
}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
// @import "~vue-perfect-scrollbar/dist/css/vue-perfect-scrollbar";

div.ps__scrollbar-y-rail {
    background: red!important;
}

.container {
    @include flex;
    @include flex-direction-row;
    @include flex-grow(1);

    height: 100%;

    .main-display {
        @include flex;
        @include flex-direction-column;
        @include flex-grow(1);

        @media(max-width: 550px) {
            width: 100vw;
            height: calc(100vh - 50px);
        }

        .action-bar {
            @include flex-shrink(0);
            @include box-sizing(border-box);
            height: $search-menu-height + 7px;
            background-color: #f7f7f7;
            @include fine-border(#d3d4d5, 'bottom');

            padding: 20px 40px;
            @include drop-shadow(1px, 1px, 2px, 1px, rgba(0,0,0,0));
            transition: all 0.3s ease-in-out;
            &.scrolled {
                @include fine-border(#cacbbc, 'bottom');
                @include basic-box-shadow;
            }
            z-index: 1;
            @include flex;
            @include flex-direction-row;

            @media (max-width: 550px) {
                padding: 12px;
                margin-left: -12px;
                margin-right: -12px;
            }

            .search {

                .search-icon {
                    left: 15px;
                }
                .clear-search {
                    right: 15px;
                }
                position: relative;
                @include flex;
                @include flex-direction-row;
                @include flex-grow(1);
                @include box-sizing(border-box);
                height: 50px;

                @media (max-width: 550px) {
                    height: 42px;
                }

                input.text {
                    @include flex-grow(1);
                    font-family: $font-family;
                    font-size: 16px;
                    font-weight: 500;
                    height: 50px;
                    @include box-sizing(border-box);
                    // @include fine-border(#e1e2e5);
                    @include fine-border(#d4d5d6);

                    // @include drop-shadow(0px, 2px, 4px, 1px, rgba(0,0,0, 0.08));
                    // @include box-shadow(0 3px 5px -1px rgba(0,0,0,.04), 0 6px 10px rgba(0,0,0,.03), 0 1px 18px rgba(0,0,0,.02));
                    @include basic-box-shadow;
                    border-radius: 8px;
                    color: $color-text-primary;
                    padding-left: 38px;
                    @include placeholders(#bbbdbf);
                    // padding: 19px 14.5px;
                    // border-top-right-radius: 0;
                    // border-bottom-right-radius: 0;
                    @include transition(box-shadow .1s ease-in-out, border-color .1s ease-in-out);
                    &:focus {

                        border-color: $color-tertiary;
                        @include box-shadow(0 3px 5px -1px rgba(102,153,255,.15), 0 6px 10px rgba(102,153,255,.1), 0 1px 18px rgba(102,153,255,.08));
                        // @include drop-shadow(0px, 2px, 4px, 1px, rgba(102,153,255, 0.25));

                    }

                    @media (max-width:  550px) {
                        height: 42px;
                        font-size: 14px;
                    }

                }

            }

            .buttons {
                @include flex;
                @include flex-direction-row;
                @include justify-content-start;
                padding-left: 10px;

                button {

                    margin-left: 5px;
                    margin-right: 5px;

                    @media(max-width: 800px) {
                        font-size: 20px;
                    }

                    @media(max-width: 550px) {
                        margin-left: 0;
                        margin-right: 0;
                    }

                }


                .toggle-right {
                    display: none;
                    @media (max-width: 1260px) {
                        display: block;
                    }
                }

            }

            @media(max-width: 1024px) {

                @include flex-direction-column;
                height: auto;

                .search {
                    margin-bottom: 10px;
                    top: 60px;
                }

                .buttons {
                    padding-left: 0;
                    position: relative;
                    top: -65px;
                    @include justify-content-end;
                    button {
                        width: 100%;
                    }
                    button:first-child {
                        margin-left: 0;
                    }
                    button:last-child {
                        margin-right: 0;
                    }
                }

            }

            @media(max-width: 738px) {
                padding-bottom: 2px;
                .search {
                    top: 40px;
                }
                .buttons {
                    top: -62px;
                    button {
                        height: 42px;
                        // height: auto;
                        max-height: 100%;
                        padding: 0;
                        margin-left: 4px;
                        margin-right: 4px;
                        button:first-child {
                            margin-left: 0;
                        }
                        button:last-child {
                            margin-right: 0;
                        }
                    }
                }
            }

        }

        .manage {
            background-color: #faf8de;
            @include flex-grow(1);
            @include flex;
            @include flex-direction-column;
            // @include flex-wrap(wrap);
            // @include flex-flow-column-wrap;
            // align-content: flex-start;
            // @include flex-flow(wrap);

            position: relative;
            box-sizing: border-box;
            padding: 20px - $card-margin 40px - $card-margin;
            // overflow: auto;

            // @include scroll-bar-custom(#afb1b3, #d4d7d9);
            @media(max-width: 550px) {
                padding: 12px;
            }


            .columns {
                @include flex;
                width: 100%;
                overflow: visible;

                .column {
                    margin-left: 7.5px;
                    margin-right: 7.5px;



                    &:first-child {
                        margin-left: 10px;
                    }

                    &:last-child {
                        margin-right: 10px;
                    }

                    // display: inline-block;
                    // width: 1000px;
                    width: 100%;
                    // -webkit-column-count:2;
                    // column-count:2;
                    // grid-gap: $card-margin;

                    .card {

                        // &.left {
                        //     float: left;
                        // }
                        //
                        // &.right {
                        //     float: right;
                        // }
                        width: 100%;
                        display: inline-block;
                        // display: flex;
                        // flex-grow: 1;
                        // width: calc(50% - #{$card-margin * 2});
                        // padding-bottom: 8px;
                        // padding: 20px;
                        // flex: 1 calc(50% - #{$card-margin * 2});

                        // height: 100%;
                        // width: calc(50% - #{$card-margin * 2});
                        @include box-sizing(border-box);
                        margin-bottom: $card-margin;
                        margin-top: $card-margin;


                        // margin: $card-margin $card-margin 15px;
                        background: #fff;
                        @include fine-border(#d4d5d6);
                        // @include box-shadow(0 3px 5px -1px rgba(0,0,0,.04), 0 6px 10px rgba(0,0,0,.03), 0 1px 18px rgba(0,0,0,.02));
                        @include basic-box-shadow;
                        border-radius: 8px;
                        @include transition(box-shadow .08s ease-in-out);
                        &:hover {
                            @include box-shadow(0 4px 8px -1px rgba(0,0,0,.08), 0 7px 14px 1px rgba(0,0,0,.05), 0 2px 18px 2px rgba(0,0,0,.04));
                        }

                        @media(max-width: 550px) {
                            margin-top: 5px;
                            margin-bottom: 5px;
                            margin-left: 0px;
                            margin-right: 0px;

                        }
                    }
                    @media (max-width: 550px) {
                        margin: 0;
                        padding-top: 10px;
                        &:first-child {
                            margin-left: 0px;
                        }

                        &:last-child {
                            margin-right: 0px;
                        }
                    }
                }

            }

            .infinite-loading {

                display: block;
                margin-left: auto;
                margin-right: auto;
                min-width: 1px;

            }
        }
    }

    .menu {
        background: #fff;
        padding: 30px 40px 40px;
        width: 460px;
        border-left: 1px solid #d4d5d6;
        @include flex;
        @include flex-direction-column;
        @include box-sizing(border-box);
        @include flex-shrink(0);

        .upload-title {
            color: $color-text-secondary;
            font-weight: 700;
            font-size: 12px;
            @include fine-border($color-primary, 'bottom');
            text-transform: uppercase;
            padding-bottom: 7px;
            margin-bottom: 25px;
            letter-spacing: 0.1em;
            @media(max-width: 600px) {
                margin-bottom: 20px;
            }
        }

        .form-group {
            @include flex;
            @include flex-direction-column;
            margin-bottom: 25px;
            @media(max-width: 600px) {
                margin-bottom: 20px;
            }
            @include flex-grow(0);
            &:last-of-type {
                @include flex-grow(1);
                @media(max-height: 780px) {
                     @include flex-grow(0);
                    height: 60px;
                }

            }
            &.category-group{
                position: relative;
                z-index: 2;
            }
            &.upload-input-group{
                position: relative;
                z-index: 1;
            }
            .disabled-title{
                pointer-events: none;
                cursor: auto;
                background: #f8f8f8;
            }
            .default__title--container{
                padding-top: 5px;

                label:before{
                    margin-top: 0;
                }
                .styled-checkbox:checked + label:after{
                    top: 8px;
                }
            }

            label {
                padding-left: 13px;
                display: block;
                color: $color-text-tertiary;
                line-height: 1;
                margin-bottom: 8px;
                text-transform: uppercase;
                font-weight: 600;
                font-size: 10px;
                letter-spacing: 0.08em;
            }

            .multiselect,
            input,
            textarea {
                font-family: $font-family;
                display: block;
                @include fine-border(#cccccc);
                border-radius: 8px;
                padding: 14.5px 12px;
                color: $color-text-primary;
                font-weight: 400;
                font-size: 16px;
                &:focus,
                &:hover {
                    @include fine-border($color-tertiary);
                }
                @include placeholders(#bbbdbf);
            }

            .multiselect {
                padding: 0;
                z-index: 1;
                height: 50px;
                box-sizing: border-box;
                padding-top: 5px;
                .multiselect__tags {
                    border-radius: 8px;
                    padding: auto;
                }

            }

            textarea {
                resize: none;
                overflow: hidden;
                @media(max-width: 600px) {
                    height: 50px;
                }
            }

            .upload-area,
            input,
            textarea {
                // &.drag,
                // &:focus {
                //     @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
                // }
                @include transition(box-shadow .1s ease-in-out, border-color .1s ease-in-out);
                &.drag,
                &:focus {

                    border-color: $color-tertiary;
                    @include box-shadow(0 3px 5px -1px rgba(102,153,255,.15), 0 4px 8px rgba(102,153,255,.06), 0 1px 12px rgba(102,153,255,.03));

                }
            }

            .upload-area-button {

                display: none;
                @media(max-height: 780px) {
                    display: block;
                }
                height: 40px;
                min-height: 40px;
                display: none;
                font-size: 14px;
                font-weight: 500;
                color: $color-text-primary;
                @include fine-border(#d2d4d6);
                padding: 10px 20px;
                background: #f7f7f7;
                max-width: 100%;
                overflow: hidden;
                margin-bottom: 30px;
                &:hover {
                    color: $color-primary;
                    background: #fff;
                    @include fine-border($color-primary);
                }
            }

            .upload-area {
                @media(max-height: 780px) {
                    display: none;
                }
                @include fine-border(#cccccc);
                border-radius: 8px;
                position: relative;
                @include flex;
                @include justify-content-center;
                @include align-items(center);
                @include flex-grow(1);
                padding: 20px;
                cursor:pointer;
                &.drag {
                    @include fine-border($color-tertiary);
                    @include drop-shadow(0px, 2px, 5px, 1px, rgba(102, 153, 255, 0.15));
                }
                &:hover {
                    @include fine-border($color-tertiary);

                    .has-upload,
                    .no-upload {
                        pointer-events: all;
                    }
                }

                .wrapper, .no-upload {
                    height: 100%;
                    width: 100%;

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
                    @include flex;
                    @include flex-direction-column;
                    @include justify-content-center;
                    width: 100%;
                    word-break: break-word;
                }

                .no-upload {
                    z-index: 1;

                    p {
                        margin-bottom: 20px;
                    }

                    button {
                        height: 40px;
                        font-size: 14px;
                        font-weight: 500;
                        color: $color-text-tertiary;
                        @include fine-border(#d2d4d6);
                        padding: 10px 20px;
                        @include box-shadow(initial);
                        &:hover {
                            color: $color-primary;
                            @include fine-border($color-primary);
                        }
                    }
                }

                input {
                    z-index: 200;
                    // z-index: -1;
                    cursor: pointer;
                    position: absolute;
                    opacity: 0;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                }
            }
        }

        .upload-main-button {
            margin-top: 5px;
        }

        .dashboard-action-buttons.close {
            display: none;
        }

        @media(max-width: 1260px) {
            z-index: 666;
            position: fixed;
            right: 0;
            transform: translateX(100%);
            height: 100vh;
            top: 0;
            @include transition(all .1s ease-in-out);
            .dashboard-action-background {
                opacity: 0;
                @include transition(opacity .1s ease-in-out);
            }
            &.active {
                height: auto;
                transform: translateX(0%);
                .dashboard-action-background {
                    opacity: 0.7;
                    content: '';
                    top: 0;
                    left: -100vw;
                    position: fixed;
                    height: 100vh;
                    width: 100vw;
                    background: rgb(10, 10, 10);
                }
            }

            .dashboard-action-buttons.close {
                @include flex;
                @include flex-direction-column;
                @include justify-content-center;
                cursor: pointer;
                color: $color-primary;
                font-size: 18px;
                font-weight: 600;
                border-bottom: 1px solid #d4d5d6;
                margin-left: 0;
                margin-right: 0;
                padding-left: 30px;
                padding-right: 30px;
                margin-top: 0;
                padding-top: 24px;
                margin-bottom: 0;
                padding-bottom: 23px;
                margin-top: -30px;
                margin-left: -40px;
                margin-right: -40px;
                // padding-bottom: 12px;
                background: #f7f7f7;
                @include transition(all .1s ease-in-out);
                &:hover {
                    background: $color-primary;
                    color: #fff;
                }
                p {
                    margin-bottom: 0;
                    margin-top: 0;
                }

                i {
                    margin-left: 10px;
                    font-size: 18px;
                }
            }
        }



    }

}

.error {
    border: 1px solid #ff6756!important;
}
.error-m .multiselect {
    border: 1px solid #ff6756!important;
}
</style>
