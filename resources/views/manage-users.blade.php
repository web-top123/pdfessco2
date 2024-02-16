@extends('layouts.master')

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('content')

    <div class="columns manage-users">
        <div class="action columns">
            <div class="column">
                <input id="users-search" type="text" placeholder="Search...">
            </div>
            <div class="column is-narrow">
                <button class="button-base" @click="modals.create = true">Create User</button>
                <button class="button-base" id="deselect-all">Deselect All</button>
                <button class="button-base button-delete" @click="openDelete(0)">Delete</button>
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
                {{-- <div class="table-pagination">
                    <span class="page-size">15</span>
                    <span class="page-size active">20</span>
                    <span class="page-size">25</span>
                    <span class="page-size">30</span>
                    <span class="page-size">40</span>
                    <span class="page-size">50</span>
                </div> --}}

                <div class="table-pages">
                    <span class="pages-current">@{{currentPage}}</span>/<span class="pages-total">@{{totalPages}}</span>
                    <span id="prev-page" class="prev-page"><i class="fa fa-chevron-left"></i></span>
                    <span id="next-page" class="next-page"><i class="fa fa-chevron-right"></i></span>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('modals')
    <modal-create-user v-if="modals.create" :show="modals.create" @close="modals.create = false"></modal-create-user>
    <modal-delete-user v-if="modals.delete" :show="modals.delete" :selected="this.selected" :multi="this.selected.length > 1" :user="modals.deleteId" @close="modals.delete = false" @delete="finishedDelete"></modal-delete-user>
    <modal-edit-user v-if="modals.edit" :show="modals.edit" :user="modals.editUser" @close="modals.edit = false" @edit="finishedEdit"></modal-edit-user>

@endpush

@push('scripts')
    <script>
        var dtUrl = '{!! url('/admin/manage-users/data') !!}';
    </script>
    <script src="{{ mix('js/manage-users.js') }}"></script>
@endpush
