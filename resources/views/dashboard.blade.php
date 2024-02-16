@extends('layouts.master')

@section('content')
    <navbar></navbar>
    <transition name="fade" mode="out-in">
        <router-view></router-view>
    </transition>
@endsection

@push('scripts')
        <script>
        var __Data = {
                user: Object.assign( {!! Auth::user() !!}, { admin : {!! Auth::user()->can('manage')? 1 : 0 !!}, timeouts: {!! Auth::user()->timeouts() !!} })
            };
        </script>
        <script src="{{ mix('js/dashboard.js') }}"></script>
@endpush
