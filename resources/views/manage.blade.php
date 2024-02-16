@extends('layouts.master')

@section('content')
        <navbar></navbar>
        <transition name="fade" mode="out-in">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
@endsection

@push('scripts')
    <script>
        var __Data = {
            user: Object.assign({!! Auth::user() !!}, { admin : {!! Auth::user()->can('manage') !!} ,  superadmin: {!! Auth::user()->can('create_admins') ? 1 : 0 !!}, timeouts: {!! Auth::user()->timeouts() !!} }),
        }

    </script>
    <script src="{{ mix('js/manage.js') }}"></script>
@endpush
