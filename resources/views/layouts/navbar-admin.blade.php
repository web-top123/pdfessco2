<nav id="main-navigation">
    <div class="columns">

        <div class="column is-2 brand">

            <a href="{{ url()->current() }}">PDF<span>Glue</span></a>

        </div>

        <div class="column menu">

            <div class="links">
                    <a href="{{ url('/admin') }}">File Management</a>
                    <a href="{{ url('/admin/manage-users') }}">PdfGlue Users</a>
                    <a href="{{ url('/admin/manage-admins') }}">Administrative Users</a>
                    <a href="{{ url('/dashboard') }}">Create Document</a>
                    <a href="{{ url('/about') }}">About</a>
                    <a href="{{ url('/contact') }}">Contact</a>
            </div>
            @auth
            <div class="account" @click="navbar.dropdown = !navbar.dropdown">
                <span>{{ Auth::user()->name }}</span>
                <i v-if="!navbar.dropdown" class="fa fa-chevron-down"></i>
                <i v-if="navbar.dropdown" class="fa fa-chevron-up"></i>
            </div>
            @endif
        </div>
    </div>
    <div v-show="navbar.dropdown" class="context-menu">
        <div class="context-menu-item">My Account</div>
        <div class="context-menu-item">Log Out</div>
    </div>
</nav>
