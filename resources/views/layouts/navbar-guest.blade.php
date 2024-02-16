<nav id="main-navigation">
    <div class="columns">

        <div class="column is-2 brand">
            <a href="{{ url()->current() }}">PDF<span>Glue</span></a>
        </div>

        <div class="column menu">

            <div class="links">

               
                <a href="{{ url('/about') }}">
                    About
                </a>

                <a href="{{ url('/contact') }}">
                    Contact
                </a>

            </div>


            <div id="navbar-dropdown" class="account" @click.prevent="navbar.dropdown = !navbar.dropdown">
                <span>Guest</span>
                <i v-if="!navbar.dropdown" class="fa fa-chevron-down"></i>
                <i v-if="navbar.dropdown" class="fa fa-chevron-up"></i>
            </div>


        </div>

    </div>
</nav>

<div v-show="navbar.dropdown" class="context-menu" >
    <div class="context-menu-item"  @click="navbar.loginModal = true">Login</div>
    <div class="context-menu-item"  @click="navbar.registerModal = true">Register</div>
</div>

@push('modals')
    <modal-login :show="navbar.loginModal" @close="navbar.loginModal = false" @register="navbar.loginModal = false; navbar.registerModal = true" @login="login"></modal-login>
    <modal-register :show="navbar.registerModal" @close="navbar.registerModal = false" @register="register"></modal-register>
@endpush
