require('./bootstrap');

import Ps from 'vue-perfect-scrollbar'

import CategoryItem from './components/admin/Category-item.vue'
import InfiniteLoading from 'vue-infinite-loading'
import ModalCreateCategory from './components/modals/Modal-create-category.vue';
import ModalDeleteCategory from './components/modals/Modal-delete-category.vue';
import ModalEditCategory from './components/modals/Modal-edit-category.vue';
import ModalEditPdf from './components/modals/Modal-edit-pdf.vue';
import ModalUploadToCategory from './components/modals/Modal-upload-to-category.vue';
import ModalMoveFilesToCategory from './components/modals/Modal-move-files-to-category.vue';
import Multiselect from 'vue-multiselect'




$(document).ready(function () {
    $('.manage-container').height($('.manage-upload').height());
});


const navbar = require('./navbar')()

const app = new Vue({
    mixins: [navbar],
    el: '#app',
    data: {
        list: list,
        categories: {
            selected: {
                categories: [],
                files: [],
            },
            data: categories,
        },
        upload: {
            file: undefined,
            drag: false,
            select: undefined,
        },
        search: {
            query: '',
        },
        modals: {
            delete: false,
            upload: false,
            move: false,
            create: false,
            edit: false,
            editCategory: false,
        },
        modalData: undefined,
        scrollShadow: false,
    },
    components: {
        ModalCreateCategory,
        ModalDeleteCategory,
        ModalEditCategory,
        ModalEditPdf,
        ModalUploadToCategory,
        ModalMoveFilesToCategory,
        Multiselect,
        CategoryItem,
    },
    computed: {
        canOpenMove() {

            return true;

        },
        listCreate() {
            return this.list.slice().map((item) => { if (item.depth > 3 || (item.files_count > 0)) { return Object.assign({'$isDisabled': true},  item);} return item;});
        },
        listUpload() {
            return this.list.slice().map((item) => {
                if (item.children_count) {
                    return Object.assign({'$isDisabled': true},  item);
                };
                return item;
            });
        }
    },
    mounted() {
        const _self = this;
        Ps.initialize(this.$refs.mainScrollbar, {
            suppressScrollX: true,
        });

        $(window).resize(function() {
            $('.manage-container').height($('.manage-upload').height() - 10);
            Ps.update(_self.$refs.mainScrollbar);
        });

        this.$refs.mainScrollbar.addEventListener('ps-scroll-y', () => {

            if (this.$refs.mainScrollbar.scrollTop > 0) {
                this.scrollShadow = true;
            }

        });

        this.$refs.mainScrollbar.addEventListener('ps-y-reach-start', () => {

            this.scrollShadow = false;

        });
    },
    methods: {

        request(cb) {
            axios.get('/admin/categories', {params: {
                offset: this.categories.data.length,
                q: this.search.query,
            }})
            .then(({data}) => {
                this.categories.data = this.categories.data.concat(data);
            })
            .catch(({data}) => console.error(data));
        },

        search() {

        },

        processFile(event) {
            this.upload.file = event.target.files[0];
        },
        // request() {
        //     this.modalData = undefined;
        //     axios.get('/admin/categories' + (this.search.query ? '?q=' + this.search.query : ''))
        //         .then(({
        //             data
        //         }) => {
        //             this.categories.data = data.categories;
        //         })
        //         .catch(({
        //             data
        //         }) => {
        //             console.error(data);
        //         });
        // },
        uploadFile(ev) {

            this.modalData = undefined;

            const data = new FormData();
            data.append('title', ev ? ev.title : this.upload.title);
            data.append('description', ev ? ev.description : this.upload.description);
            data.append('category', ev ? ev.select.id : this.upload.select.id);
            data.append('file', ev ? ev.file : this.upload.file);

            axios.post('/admin/file', data)
                .then(({
                    data
                }) => {
                    this.newFile(data.file);

                    if (!ev) {
                        this.upload.title = '';
                        this.upload.description = '';
                        this.upload.select = undefined;
                        this.upload.file = undefined;
                    }

                })
                .catch((error) => {
                    console.error(error);
                });

        },
        traverseRecursive(arr, children, callback, stop = true) {
            if (!stop) {
                return false;
            }
            for (let i = 0; i < arr.length; i++) {

                let skip = false;

                if (!stop) {
                    return false;
                }
                stop = callback(arr[i], arr, i, children)

                if (stop === 'decrement')
                {
                    stop = true;
                    skip = true;
                }

                if (!stop) {
                    return false;
                }
                if (!skip) {

                    stop = this.traverseRecursive(arr[i][children], children, callback)

                } else {

                    i--;

                }
            }
            return stop;
        },

        newFile(file) {
            this.traverseRecursive(this.categories.data, 'children', function(item) {
                if (parseInt(item.id) === parseInt(file.category_id)) {
                    let inserted = false;
                    for (let i = 0; i < item.files.length; i++) {
                        if (item.files[i].name.toLowerCase() > file.name.toLowerCase()) {
                            item.files.splice(i, 0, file);
                            inserted = true;
                            break;
                        }
                    }
                    if (!inserted) {
                        item.files.push(file);
                    }
                    return false;
                }
                return true;
            });

            this.modalData = undefined;
        },
        deleteItems(ev) {

            let selected = {}

            if (this.modalData) {

                selected = this.modalData;

            } else {

                selected = {
                    files: this.categories.selected.files,
                    categories: this.categories.selected.categories,
                }

            }

            this.traverseRecursive(this.categories.data, 'children', function(item, parent, index) {

                const indCat = selected.categories.indexOf(item.id);

                if (indCat > -1) {

                    parent.splice(index, 1);
                    selected.categories.splice(indCat, 1);
                    return 'decrement';

                }

                for (let i = 0; i < item.files.length; i++) {

                    const ind = selected.files.indexOf(item.files[i].id);

                    if (ind > -1) {

                        item.files.splice(i--, 1);
                        selected.files.splice(ind, 1);

                    }

                }

                return true;
            });

            this.modalData = undefined;

        },
        openModal(type, data) {
            this.modalData = Object.assign({}, data);
            this.modals[type] = true;

        },
        moveItems(ev) {

            this.request();
            this.modalData = undefined;
            this.modals.move = false;
            this.categories.selected.categories = [];
            this.categories.selected.files = [];
        },
        createItems(ev) {

            this.list = ev.list;
            ev = ev.category;

            this.modalData = undefined;

            if (!ev.parent_id) {
                for(let i = 0; i < this.categories.data.length; i++) {
                    if (this.categories.data[i].name > ev.name) {
                        this.$set(this.categories.data, i, ev);
                        return;
                    }
                }
                this.categories.data.push(ev);
                return;
            }

            this.traverseRecursive(this.categories.data, 'children', function(item, parent, index) {

                if (item.id === ev.parent_id) {

                    for(var i = 0; i < item.children.length; i++) {
                        if (item.children[i].name > ev.name) {
                            item.children.splice(i, 0, ev);
                            return false;
                        }
                    }
                    item.children.push(ev);
                    return false;
                }

                return true;
            });

        },
        editItem(ev) {

            this.list = ev.list;
            ev = ev.category;


            this.traverseRecursive(this.categories.data, 'children', function(item, parent, index) {

                for (let i = 0; i < item.files.length; i++) {

                    if (item.files[i].id === ev.id) {
                        item.files[i].name = ev.name;
                        item.files[i].description = ev.description;
                        return false;
                    }
                }

                return true;
            });

            this.modalData = undefined;


        },
        editItemCategory(ev) {

            this.list = ev.list;
            ev = ev.category;

            this.traverseRecursive(this.categories.data, 'children', function(item, parent, index) {



                if (item.id === ev.id) {

                    item.name = ev.name;
                    return false;

                }

                return true;
            });

            this.modalData = undefined;

        }
    }
})
