<template>
<div class="columns manage-users">
    <div class="action global-timeout-wrapper">
        <div class="global-timeout-box">
            <p>Set Global Timeout</p>
            <div class="columns">
                <div class="column">
                    <div class="timeout-input"><input type="number" min="1" max="43220" v-model="timeoutMinutes"><span>Minutes</span></div>
                </div>
                <div class="column"><button class="button-base fill timeout-global-save" :class="{'disabled' : globalTimeout === timeoutMinutes }" @click="setGlobalTimeout">Save</button><button :class="{'disabled' : globalTimeout === timeoutMinutes }" class="button-base timeout-global-cancel"
                        @click="resetGlobalTimeout">Cancel</button></div>
            </div>
        </div>
    </div>

    <div class="action individual-timeout-wrapper">
        <p class="title">Set Individual Timeout</p>
    </div>

    <div v-if="width <= 960" class="action manage-timeouts">
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

<div v-if="width > 960" class="action manage-timeouts">
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

    <div v-if="admin" class="manage-users-dropdown admin" :class="{active: dropdown}">
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
</div>

<div class="table-wrapper manage-timeouts">
    <div class="table-container">
        <table id="table">
            <thead>
                <tr>
                    <th></th>
                    <th>User <i class="fa fa-caret-up"></i><i class="fa fa-caret-down"></i></th>
                    <th>Current Timeout</th>
                    <th class="table-action">Edit</th>
                    <th class="table-action">Reset Timeout</th>
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
            url: '/timeout/data/users',
            urlUsers: '/timeout/data/users',
            urlAdmins: 'timeout/data/admins',
            admin: false,
            width: 0,
            timeoutMinutes: 0,
        }
    },
    computed: {
        globalTimeout() {
            return this.$store.state.user.timeouts.global;
        }
    },
    methods: {
        setRefresh(callback) {
            this.refresh = callback;
        },
        setUrl(callback) {
            this.changeUrl = callback;
        },
        deselectAll() {
            this.selected = [];
        },
        select(id) {
            this.selected.push(id);
        },
        isSelected(id) {
            return this.selected.indexOf(parseInt(id)) > -1;
        },
        deselect(id) {
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
        setGlobalTimeout() {

            if (this.globalTimeout === this.timeoutMinutes) {
                return;
            }

            if (this.timeoutMinutes < 1) {
                return;
            }

            axios.post('/timeout/global', {
                timeout: this.timeoutMinutes
            }).then(({
                data
            }) => {
                this.$store.commit('setGlobalTimeout', data.timeout);
                this.timeoutMinutes = this.globalTimeout;
            }).catch(e => console.error(e));
        },
        resetGlobalTimeout() {
            if (this.globalTimeout === this.timeoutMinutes) {
                return;
            }

            this.timeoutMinutes = this.globalTimeout;
        },
        setIndividualTimeout(id, minutes) {
            axios.post('/timeout/user/update', {
                userId: id,
                timeout: minutes
            }).catch(e => console.error(e));
        },
        resetIndividualTimeout(id) {
            axios.post('/timeout/user/delete', {
                userId: id
            }).catch(e => console.error(e));
        }
    },
    mounted() {
        this.timeoutMinutes = this.globalTimeout;
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
                pageLength: 15,
                ajax: '/timeout/data/users',
                order: [
                    [1, "asc"]
                ],
                bScrollCollapse: true,
                columns: [{
                        data: null,
                        defaultContent: '',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                    },
                    {
                        data: 'name',
                        render: function(data, type, full) {
                            return data;
                        }
                    },
                    {
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                        data: 'timeout',
                        render: function(data, type, full) {
                            return '<span class="timeout-individual-input-text ' + (data ? 'active' : '') + '" id="timeout-individual-input-text-' + full.id + '">' + (data ? data.timeout + ' minutes' : 'Global Timeout') +
                                '</span>' + '<span id="timeout-individual-input-' + full.id + '" class="timeout-individual-input"><input id="timeout-individual-input-input-' + full.id +
                                '" min="1" max="43220" type="number" value="' + (full.timeout ? full.timeout.timeout : '60') + '"><button type="button" class="timeout-table-button timeout-save-button" data-id="' + full.id +
                                '">Save</button><button type="button" class="timeout-table-button timeout-cancel-button" data-id="' + full.id + '">Cancel</button></span>';
                        }
                    },
                    {
                        data: null,
                        className: 'table-action',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                        render: function(data, type, full) {
                            return '<button class="timeout-table-button timeout-edit-button" data-id="' + full.id + '" type="button">Edit</button>';
                        },
                    },
                    {
                        data: null,
                        className: 'table-action',
                        searchable: false,
                        "bSearchable": false,
                        "orderable": false,
                        render: function(data, type, full) {
                            return '<button id="timeout-reset-button-' + full.id + '" class="timeout-table-button timeout-reset-button ' + (!full.timeout ? 'disabled' : '') + '" data-id="' + full.id +
                                '" type="button">Set to Global</button>';
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

            _this.setRefresh(function() {
                dt.draw();
            });;

            _this.setUrl(function(url) {
                dt.ajax.url(url).load();
            });

            $(document).on('change', '.table-checkbox', function() {
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

            $(document).on('click', '#deselect-all', function() {
                _this.deselectAll();
                $('.table-checkbox').prop('checked', false);
                $('.checked').removeClass('checked');
            });

            $(document).on('keyup', '#users-search', function() {
                dt.search($(this).val()).draw();
            });

            $(document).on('click', '#prev-page', function() {
                dt.page('previous').draw('page');
            });

            $(document).on('click', '#next-page', function() {
                dt.page('next').draw('page');
            });

            var editId = undefined;

            $(document).on('click', '.timeout-edit-button', function() {
                $('.timeout-individual-input').hide();
                $('.timeout-individual-input-text').show();

                var id = $(this).data('id');
                $('#timeout-individual-input-' + id).show();
                $('#timeout-individual-input-text-' + id).hide();
                $('#timeout-individual-input-input-' + id).focus();

                editId = id;
            });

            $(document).on('click', '.timeout-reset-button', function() {

                if ($(this).hasClass('disabled')) {
                    return;
                }

                $('.timeout-individual-input').hide();
                $('.timeout-individual-input-text').show();

                var id = $(this).data('id');
                $('#timeout-individual-input-text-' + id).html('Global Timeout');
                $(this).addClass('disabled');
                _this.resetIndividualTimeout(id);

                editId = undefined;
            });


            $(document).on('click', '.timeout-save-button', function() {
                var id = $(this).data('id');
                var minutes = parseInt($('#timeout-individual-input-input-' + id).val());

                if (minutes && minutes > 0) {
                    _this.setIndividualTimeout(id, minutes);

                    $('#timeout-reset-button-' + id).removeClass('disabled');
                    $('#timeout-individual-input-text-' + id).html(minutes + ' minutes');
                    $('.timeout-individual-input').hide();
                    $('.timeout-individual-input-text').show();
                    editId = undefined;
                }

            });

            $(document).on('click', '.timeout-cancel-button', function() {
                var id = $(this).data('id');

                $('.timeout-individual-input').hide();
                $('.timeout-individual-input-text').show();

                var minutes = parseInt($('#timeout-individual-input-text-' + id).html().split(' ')[0]);
                if (minutes) {
                    $('#timeout-individual-input-input-' + id).val(minutes);
                } else {
                    $('#timeout-individual-input-input-' + id).val(60);
                }

                editId = undefined;

            });

            window.addEventListener('keypress', function(e) {
                if (e.keyCode === 13) {
                    var id = editId;
                    var minutes = parseInt($('#timeout-individual-input-input-' + id).val());

                    if (minutes && minutes > 0) {
                        _this.setIndividualTimeout(id, minutes);

                        $('#timeout-reset-button-' + id).removeClass('disabled');
                        $('#timeout-individual-input-text-' + id).html(minutes + ' minutes');
                        $('.timeout-individual-input').hide();
                        $('.timeout-individual-input-text').show();
                        editId = undefined;
                    }

                }
            });

        });
    }
};
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";
@import "bulma/bulma";

.global-timeout-wrapper {
    border-bottom: none;
    height: auto;
    padding-top: 30px;  
    .global-timeout-box {
        @include flex;
        @include flex-direction-column;
        @include flex-grow(1);
        border-bottom: 1px solid #d4d5d6;
        p {
            font-size: 32px;
            color: #404040;
            font-weight: 300;
            margin-bottom: 20px;
        }

        div.columns {
            div.column {
                .timeout-input {
                    @include flex;
                    span {
                        font-size: 16px;
                        color: #666666;
                        font-weight: 500;
                        padding-top: 15px;

                    }

                    input {
                        @include flex-grow(1);
                        font-family: $font-family;
                        font-size: 16px;
                        font-weight: 500;
                        height: 50px;
                        width: 83px;
                        margin-bottom: 26px;
                        margin-right: 12px;
                        @include box-sizing(border-box);
                        @include fine-border(#d4d5d6);
                        @include basic-box-shadow;
                        border-radius: 8px;
                        color: $color-text-primary;
                        @include placeholders(#bbbdbf);
                        @include transition(box-shadow 0.1s ease-in-out, border-color 0.1s ease-in-out);
                        padding-left: 15px;
                        &:focus {
                            border-color: $color-tertiary;
                            @include box-shadow(0 3px 5px -1px rgba(102,153,255,.15), 0 6px 10px rgba(102,153,255,.1), 0 1px 18px rgba(102,153,255,.08));
                        }
                    }
                }

                &:first-child {
                    @include flex-grow(0);
                    width: 200px;
                }

                &:last-child {


                    .timeout-global-save {

                        &.disabled {
                            background-color: #cccccc;
                            color: #ffffff;
                            &:hover {
                                @include box-shadow(0 3px 5px -1px rgba(0, 0, 0, 0.04), 0 6px 10px rgba(0, 0, 0, 0.03), 0 1px 18px rgba(0, 0, 0, 0.02));
                            }
                            cursor: auto;
                        }
                        margin-left: 20px;
                        margin-right: -10px;
                        padding-top: 13px;

                    }

                    @media (max-width: 768px) {
                        margin-top: -30px;
                        .timeout-global-save {
                            margin-bottom: 20px;
                            margin-left: 0;
                            width: 102px;
                        }
                    }
                    .timeout-global-cancel {
                        border: none;
                        background: none;
                        box-shadow: none;
                        padding-top: 13px;
                        font-weight: 400;
                        &:hover {
                            color: $color-primary;
                        }

                        &.disabled {
                            color: #bbbdbf;
                            cursor: auto;
                        }
                    }
                }
            }
        }
    }
}

.individual-timeout-wrapper {
    border-bottom: none;
    height: auto;
    padding-top: 10px;
    padding-bottom: 0;
    p {
        font-size: 32px;
        color: #404040;
        font-weight: 300;
        margin-bottom: 0;
    }
}

.action.manage-timeouts {
    border-bottom: 0;
}

.table-wrapper.manage-timeouts {
    background: #f7f7f7;
}

.dataTables_wrapper {
    min-height: 0!important;
}
</style>
