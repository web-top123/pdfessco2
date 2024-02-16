require('./bootstrap');

require('datatables.net');

import ModalCreateUser from './components/modals/Modal-create-user.vue';
import ModalDeleteUser from './components/modals/Modal-delete-user.vue';
import ModalEditUser from './components/modals/Modal-edit-user.vue';



const navbar = require('./navbar')()

const app = new Vue({
    mixins: [navbar],
    el: '#app',
    components: {
        ModalCreateUser,
        ModalDeleteUser,
        ModalEditUser,
    },
    data: {
        refresh: () => {},
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
    },
    methods: {
        setRefresh(callback){
                this.refresh = callback;
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
        }
    },
});


$(() => {

    const dt = $('#table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 20,
        ajax: dtUrl,
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
                    return '<input id="user-' + full.id + '" type="checkbox" data-id="' + full.id + '" class="table-checkbox styled-checkbox" ' + (app.isSelected(full.id) ? 'checked' : '')  +'><label for="user-' + full.id + '"></label>' + data;
                }
            },
            {
                data: 'email',
            },
            {
                data: 'id',
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

    app.setRefresh(function () {
        dt.draw();
    });;

    $(document).on('change', '.table-checkbox', function () {
        const id = $(this).data('id');
        const val = $(this).prop('checked');

        if (val) {

            $(this).parent().parent().addClass('checked');
            app.select(id);

        } else {

            $(this).parent().parent().removeClass('checked');
            app.deselect(id);
        }

    });

    $(document).on('click', '#deselect-all', function () {
        app.deselectAll();
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
        app.openEdit(
            {
                id: $(this).data('id'),
                name: $(this).data('name'),
                email: $(this).data('email'),
             }
        );
    });

    $(document).on('click', '.delete-user', function () {
        app.openDelete($(this).data('id'));
    });

    dt.on('draw.dt', function () {

        app.setCurrentPage(dt.page.info().page + 1);
        app.setTotalPages(dt.page.info().pages);

        $('.table-checkbox').each(function () {
            if ($(this).prop('checked')) {
                $(this).parent().parent().addClass('checked');
            }
        });

    });

});
