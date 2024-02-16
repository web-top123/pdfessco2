<nav id="main-navigation">
    <div class="columns">

        <div class="column is-2 brand">
            <a href="/">PDF<span>Glue</span></a>
        </div>

        <div class="column menu">

            <div class="links">

                @can('manage')

                <a href="{{ url('/admin') }}" class="{{ !Request::is('admin') ?: 'active' }}">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    File Management
                </a>

                <a href="{{ url('/admin/manage-users') }}" class="{{ !Request::is('admin/manage-users') ?: 'active' }}">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    Pdfglue Users
                </a>

                    @can('create_admins')

                    <a href="{{ url('/admin/manage-admins') }}" class="{{ !Request::is('admin/manage-admins') ?: 'active' }}">
                        <i class="fa fa-briefcase" aria-hidden="true" style="padding-top: 2px;"></i>
                        Administrative Users
                    </a>

                    @endcan

                <a href="{{ auth()->user()->isAdmin() ? url('admin#/dashboard') : url('dashboard') }}" class="{{ !Request::is('dashboard') ?: 'active' }}">
                    <i class="fa fa-pencil-square-o" aria-hidden="true" style="padding-top: 2px;"></i>
                    Create Document
                </a>

                @else
                    <a href="{{ url('/about') }}">
                        About
                    </a>

                    <a href="{{ url('/contact') }}">
                        Contact
                    </a>
                @endcan

            </div>

            @auth

            <div id="navbar-dropdown" class="account" @click.prevent="navbar.dropdown = !navbar.dropdown" :class="{'active-dropdown' : navbar.dropdown}">
                <i class="fa fa-sort-down"></i>
                <span v-text="navbar.user.name"></span>
            </div>

            @else

            <div id="navbar-dropdown" class="account" @click.prevent="navbar.dropdown = !navbar.dropdown">
                <i v-if="!navbar.dropdown" class="fa fa-sort-down"></i>
                <i v-if="navbar.dropdown" class="fa fa-sort-up"></i>
                <span>Guest</span>
            </div>

            @endauth

        </div>

    </div>
</nav>

@auth

<div v-show="navbar.dropdown" class="context-menu" v-on-click-outside="navbarOutside">
    <div class="context-menu-item"  @click="navbar.account = true">My Account</div>
    <div class="context-menu-item" @click="logout">Log Out</div>
</div>
@push('modals')
    <modal-account :show="navbar.account" :user="navbar.user" @close="navbar.account = false" @password="navbar.account = false; navbar.password = true" @user="updateUser"></modal-account>
    <modal-password :show="navbar.password" :user="navbar.user" @close="navbar.password = false"></modal-password>
@endpush

@else

<div v-show="navbar.dropdown" class="context-menu" >
    <div class="context-menu-item" :errors="navbar.errors" @click="navbar.loginModal = true">Login</div>
    <div class="context-menu-item" :errors="navbar.errors" @click="navbar.registerModal = true">Register</div>
</div>
@push('modals')
    <modal-login :show="navbar.loginModal" @close="navbar.loginModal = false" @register="navbar.loginModal = false; navbar.registerModal = true" @login="login"></modal-login>
    <modal-register :show="navbar.registerModal" @close="navbar.registerModal = false" @register="register"></modal-register>
@endpush

@endauth

@push('scripts')
    <script>
        var user = {!! auth()->user() ? auth()->user() : "{}" !!}
        var token = "{{ csrf_token() }}"
    </script>
@endpush
