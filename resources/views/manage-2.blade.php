@extends('layouts.master')

{{-- @section('navbar')
    @include('layouts.navbar')
@endsection --}}

@section('content')

    {{-- <div class="columns">

        <div class="column manage-main">

            <div class="top-action columns" :class="{scrolled: scrollShadow}">
                <div class="column">
                    <input type="text" v-model="search.query" placeholder="Search Files...">
                </div>
                <div class="column is-narrow">
                    <button type="button" class="button-base fill upload-main-button upload-top">Upload File</button>
                    <button class="button-base">Create Category</button>
                    <button class="button-base">Move Files To</button>
                    <button class="button-base button-delete">Delete</button>
                </div>
            </div>
            <div class="manage-container" ref="mainScrollbar" infinite-wrapper>
                <div class="manager columns">
                    <div class="column">
                        <category-item class="categories-list-item" v-if="index%2===0" v-for="(item, index) in categories.data" :key="item.id" :data="item" :top-level="true" :selected="categories.selected" :last="index === categories.data.length" @open="openModal($event.type, $event.data)"></category-item>
                    </div>
                    <div class="column">
                        <category-item class="categories-list-item" v-if="index%2===1" v-for="(item, index) in categories.data" :key="item.id" :data="item" :top-level="true" :selected="categories.selected" :last="index === categories.data.length" @open="openModal($event.type, $event.data)"></category-item>
                    </div>
                </div>
            </div>
        </div>

        <div class="column manage-upload">

            <p class="upload-title">File Upload</p>
            <div class="form-group">
                <label>Title</label>
                <input type="text" placeholder="Add Title..." v-model="upload.title">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea rows="5" placeholder="Add Description..." v-model="upload.description"></textarea>
            </div>

            <div class="form-group">
                <label>Category</label>
                <multiselect v-model="upload.select" :show-labels="false" :max-height="450" placeholder="Select a category..." label="name" :options="listUpload">
                    <template slot="option" scope="props">
                        <div v-bind:style="{'margin-left': (props.option.depth * 20) + 'px'}">
                            @{{props.option.name}}
                        </div>
                    </template>
                    <span slot="noResult"> No results </span>
                </multiselect>
            </div>
            <div class="form-group">
                <label class="upload-file">Upload File</label>
                <div class="upload-area" :class="{drag : upload.drag}" @dragenter="upload.drag = true" @dragleave="upload.drag = false" @drop="upload.drag = false">
                    <div v-if="!upload.file" class="no-upload" @click="$refs.fileInput.click()">
                        <p>Drag and Drop Files Here</p>
                        <p>or</p>
                        <button type="button" class="button-base simple">Click to Choose File</button>
                    </div>
                    <div v-if="upload.file" class="has-upload">@{{ upload.file.name }}</div>
                    <input name="file" type="file" accept="application/pdf"  @change="processFile($event)" ref="fileInput">
                </div>
                <button type="button" class="upload-area-button button-base simple" @click="$refs.fileInput.click()">@{{!upload.file ? 'Click to Choose File' : upload.file.name}}</button>

            </div>

            <button type="button" class="button-base fill upload-main-button" @click="uploadFile(undefined)">Upload PDF File</button>

        </div>

    </div> --}}
@endsection

@push('modals')
    {{-- <modal-create-category v-if="modals.create" @create="createItems" @close="modals.create = false" :show="modals.create" :data="modalData" :categories="listCreate"></modal-create-category>
    <modal-delete-category v-if="modals.delete" @delete="deleteItems" @close="modals.delete = false" :show="modals.delete" :data="modalData" :selected="categories.selected"></modal-delete-category>
    <modal-edit-category v-if="modals.editCategory" @edit="editItemCategory" @close="modals.editCategory = false" :show="modals.editCategory" :data="modalData"></modal-edit-category>
    <modal-edit-pdf v-if="modals.edit" @edit="editItem" @close="modals.edit = false" :show="modals.edit" :data="modalData"></modal-edit-pdf>
    <modal-move-files-to-category v-if="modals.move" @move="moveItems" @close="modals.move = false" :data="modalData" :show="modals.move" :categories="listUpload" :multi="categories.selected.files.length > 1" :has-category="categories.selected.categories.length > 0" :selected="categories.selected"  @move="moveFiles"></modal-move-files-to-category>
    <modal-upload-to-category v-if="modals.upload" @close="modals.upload = false" :data="modalData" :show="modals.upload" :categories="listUpload" @upload="uploadFile"></modal-upload-to-category> --}}
@endpush

@push('scripts')
    <script>

        var categories = {!! $categories !!};
        var list = {!! $list !!};

    </script>
    <script src="{{ mix('js/manage.js') }}"></script>
@endpush
