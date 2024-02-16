<template>
    <div class="columns manage-users">
        <div v-if="width <= 960" class="action">


            <div class="col no-parent">

                <transition name="fade" mode="in-out">

                <span v-if="!admin">
                    <button class="button-base" @click="modals.create = true">Create User</button>
                    <button class="button-base" id="deselect-all">Deselect All</button>
                    <button class="button-base button-delete" @click="openDelete(0)"><i v-if="width <= 700" class="fa fa-trash-o" aria-hidden="true"></i><span v-if="width > 700">Delete</span></button>
                </span>

                <span v-if="admin">
                    <button class="button-base" @click="modals.create = true">Create Admin</button>
                    <button class="button-base" id="deselect-all">Deselect All</button>
                    <button class="button-base button-delete" @click="openDelete(0)"><i v-if="width <= 700" class="fa fa-trash-o" aria-hidden="true"></i><span v-if="width > 700">Delete</span></button>
                </span>

                </transition>
            </div>
            <div class="col-wrapper">
                <div class="col-secondary">
                    <span v-if="$store.state.user.superadmin">
                        <transition name="fade" mode="in-out">
                            <div  class="manage-users-dropdown" :class="{active: dropdown, admin: admin}" >
                                <div v-if="!admin" class="selected" @click.stop="toggleDropdown"> <i class="fa fa-user"></i> <i class="fa fa-caret-down"></i></div>
                                <div v-if="admin" class="selected" @click.stop="toggleDropdown"> <i class="fa fa-suitcase"></i> <i class="fa fa-caret-down"></i></div>

                                    <div v-if="dropdown" class="dropdown-content">
                                        <span v-if="dropdown">
                                            <div class="item" @click="changeAdmin"><i class="fa fa-suitcase" aria-hidden="true"></i></i>Show Administrators</div>
                                        </span>
                                        <span v-if="dropdown">
                                            <div class="item" @click="changeUser"><i class="fa fa-user" aria-hidden="true"></i></i>Show Users</div>
                                        </span>
                                    </div>

                            </div>

                        </transition>
                    </span>
                </div>
                <div class="col-secondary grow-input">
                    <input id="users-search" type="text" placeholder="Search users...">
                </div>
            </div>
        </div>

        <div v-if="width > 960" class="action">
            <div class="col">

                <span>
                    <transition name="fade" mode="in-out">
                        <div v-if="!admin" class="manage-users-dropdown" :class="{active: dropdown}" >
                            <div class="selected" @click.stop="toggleDropdown"> <i class="fa fa-user"></i>Users <i class="fa fa-caret-down"></i></div>
                            <transition name="fade" mode="in-out">
                                <span v-if="dropdown">
                                    <div class="item" @click="changeAdmin"><i class="fa fa-suitcase" aria-hidden="true"></i></i>Show Administrators</div>
                                </span>
                            </transition>
                        </div>

                        <div v-if="admin" class="manage-users-dropdown admin" :class="{active: dropdown}" >
                            <div class="selected" @click.stop="toggleDropdown"> <i class="fa fa-suitcase"></i>Administrators <i class="fa fa-caret-down"></i></div>
                            <transition name="fade" mode="in-out">
                                <span v-if="dropdown">
                                    <div class="item" @click="changeUser"><i class="fa fa-user" aria-hidden="true"></i></i>Show Users</div>
                                </span>
                            </transition>
                        </div>
                    </transition>
                </span>
            </div>
            <div class="col">
                <input id="users-search" type="text" placeholder="Search users...">
            </div>
            <div class="col">

                <transition name="fade" mode="in-out">

                <span v-if="!admin">
                    <button class="button-base" @click="modals.create = true">Create User</button>
                    <button class="button-base" id="deselect-all">Deselect All</button>
                    <button class="button-base button-delete" @click="openDelete(0)">Delete</button>
                </span>

                <span v-if="admin">
                    <button class="button-base" @click="modals.create = true">Create Admin</button>
                    <button class="button-base" id="deselect-all">Deselect All</button>
                    <button class="button-base button-delete" @click="openDelete(0)">Delete</button>
                </span>

                </transition>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="table-container">
                <table id="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>User <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                            <th>Email <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                            <th>Files Created <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                            <th>Last Activity <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                            <th>Register Date <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                            <th class="table-action">Edit</th>
                            <th class="table-action">Delete</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-action-bottom">
                <div class="table-pages">
                    <span class="pages-current">{{currentPage}}</span>/<span class="pages-total">{{totalPages}}</span>
                    <span id="prev-page" class="prev-page"><i class="fa fa-chevron-left"></i></span>
                    <span id="next-page" class="next-page"><i class="fa fa-chevron-right"></i></span>
                </div>
            </div>
        </div>

        <transition name="fade" mode="out-in">
            <modal-create-user v-if="modals.create" :show="modals.create" :admin="admin" @close="modals.create = false"></modal-create-user>
            <modal-delete-user v-if="modals.delete" :show="modals.delete" :selected="this.selected" :multi="this.selected.length > 1" :user="modals.deleteId" @close="modals.delete = false" @delete="finishedDelete"></modal-delete-user>
            <modal-edit-user v-if="modals.edit" :show="modals.edit" :user="modals.editUser" @close="modals.edit = false" @edit="finishedEdit"></modal-edit-user>
        </transition>
    </div>
</template>

<script>

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

require('datatables.net');

import ModalCreateUser from './modals/Modal-create-user.vue';
import ModalDeleteUser from './modals/Modal-delete-user.vue';
import ModalEditUser from './modals/Modal-edit-user.vue';

const app = {

}

export default {
    components: {
        ModalCreateUser,
        ModalDeleteUser,
        ModalEditUser,
    },
    data() {
        return {
            refresh: () => {},
            changeUrl: () => {},
            currentPage: 1,
            modals: {
                create: false,
                delete: false,
                deleteId: undefined,
                edit: false,
                editUser: undefined,
            },
            selected: [],
            totalPages: 1,
            dropdown: false,
            url: '/admin/manage-users/data',
            urlUsers: '/admin/manage-users/data',
            urlAdmins: '/admin/manage-admins/data',
            admin: false,
            width: 0,
        }
    },
    methods: {
        setRefresh(callback){
            this.refresh = callback;
        },
        setUrl(callback) {
            this.changeUrl = callback;
        },
        deselectAll(){
            this.selected = [];
        },
        select(id){
            this.selected.push(id);
        },
        isSelected(id) {
            return this.selected.indexOf(parseInt(id)) > -1;
        },
        deselect(id){
            this.selected.splice(this.selected.indexOf(id), 1);
        },
        setCurrentPage(page) {
            this.currentPage = page;
        },
        setTotalPages(page) {
            this.totalPages = page;
        },
        openDelete(id) {
            if (id) {
                this.modals.deleteId = id;
                return this.modals.delete = true;
            }

            if (this.selected.length) {
                return this.modals.delete = true;
            }
        },
        finishedDelete() {
            this.refresh();
            this.modals.deleteId = undefined;
            this.modals.delete = false;
        },
        openEdit(user) {
            if (user) {
                this.modals.editUser = user;
                return this.modals.edit = true;
            }
        },
        finishedEdit() {
            this.refresh();
            this.modals.editUser = undefined;
            this.modals.edit = false;
        },
        changeAdmin() {
            this.changeUrl(this.urlAdmins);
            this.toggleDropdown();
            this.admin = true;
        },
        changeUser() {
            this.changeUrl(this.urlUsers);
            this.toggleDropdown();
            this.admin = false;
        },
        navbarOutside(ev) {
            window.removeEventListener('click', this.navbarOutside);
            this.dropdown = false;
        },
        toggleDropdown() {
            if (this.dropdown) {
                window.removeEventListener('click', this.navbarOutside);
                this.dropdown = false;
            } else {
                window.addEventListener('click', this.navbarOutside);
                this.dropdown = true;
            }
        },
    },
    mounted() {

        var _this = this;
        this.width = document.body.clientWidth;
        window.addEventListener('resize', (e) => {
            if (this.width <= 880 && document.body.clientWidth > 880 || this.width > 880 && document.body.clientWidth <= 880) {
                this.width = document.body.clientWidth;
            } else {
                this.width = document.body.clientWidth;
            }
        })

        $(() => {

            const dt = $('#table').DataTable({
                processing: true,
                language: {
                    processing: "Loading data..."
                },
                serverSide: true,
                pageLength: 20,
                ajax: '/admin/manage-users/data',
                order: [[1, "asc"]],
                bScrollCollapse : true,
                columns: [
                    {
                        data: null,
                        defaultContent: '',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                    },
                    {
                        data: 'name',
                        render: function (data,type,full) {
                            return '<input id="user-' + full.id + '" type="checkbox" data-id="' + full.id + '" class="table-checkbox styled-checkbox" ' + (_this.isSelected(full.id) ? 'checked' : '')  +'><label for="user-' + full.id + '"></label>' + data;
                        }
                    },
                    {
                        data: 'email',
                    },
                    {
                        searchable: false,
                        data: 'documents_count',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: null,
                        className: 'table-action',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                        render: function (data, type, full){
                            return '<i class="fa fa-pencil-square-o edit-user" aria-hidden="true" data-id="' + full.id + '" data-name="'+full.name+'" data-email="'+full.email+'"></i>';
                        },
                    },
                    {
                        data: null,
                        className: 'table-action',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                        render: function (data, type, full){
                            return '<i class="fa fa-trash-o delete-user" aria-hidden="true" data-id="' + full.id + '"></i>';
                        },
                    },
                    {
                        data: null,
                        defaultContent: '',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                    }
                ]
            });

            _this.setRefresh(function () {
                dt.draw();
            });;

            _this.setUrl(function (url) {
                dt.ajax.url(url).load();
            });

            $(document).on('change', '.table-checkbox', function () {
                const id = $(this).data('id');
                const val = $(this).prop('checked');

                if (val) {

                    $(this).parent().parent().addClass('checked');
                    _this.select(id);

                } else {

                    $(this).parent().parent().removeClass('checked');
                    _this.deselect(id);
                }

            });

            $(document).on('click', '#deselect-all', function () {
                _this.deselectAll();
                $('.table-checkbox').prop('checked', false);
                $('.checked').removeClass('checked');
            });

            $(document).on('keyup', '#users-search', function () {
                dt.search( $(this).val() ).draw();
            });

            $(document).on('click', '#prev-page', function () {
                dt.page('previous').draw('page');
            });

            $(document).on('click', '#next-page', function () {
                dt.page('next').draw('page');
            });

            $(document).on('click', '.edit-user', function () {
                _this.openEdit(
                    {
                        id: $(this).data('id'),
                        name: $(this).data('name'),
                        email: $(this).data('email'),
                     }
                );
            });

            $(document).on('click', '.delete-user', function () {
                _this.openDelete($(this).data('id'));
            });

            dt.on('draw.dt', function () {

                _this.setCurrentPage(dt.page.info().page + 1);
                _this.setTotalPages(dt.page.info().pages);

                $('.table-checkbox').each(function () {
                    if ($(this).prop('checked')) {
                        $(this).parent().parent().addClass('checked');
                    }
                });

            });

        });


    }
};


</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
@import "~bulma/bulma";


</style>
