<div class="presentation-navbar">
    <div class="container">
        <a class="logo" :href=" user ? '/dashboard' : '/' ">

            @if(request()->path()==='/')
            <img src="/images/PDFGlue_Logo_White.png">
            @else
            <img src="/images/PDFGlue_Logo_Blue.png">
            @endif
        </a>

        <div class="links">
            <a class="link {{ request()->path() === 'about' ? 'active' : '' }}" href="/about">
                <div class="text">
                    <p>About</p>
                </div>
            </a>
            <a class="link {{ request()->path() === 'contact-us' ? 'active' : '' }}" href="/contact-us">
                <div class="text">
                    <p>Contact</p>
                </div>
            </a>

        </div>

        <div v-cloak class="user" :class="{active: dropdown}">
            <a v-if="!user" class="link {{ request()->path() === 'login' ? 'active' : '' }}" href="/login">
                <div class="text">
                    <p>Log In</p>
                </div>
            </a>
            <div v-if="user" class="account" :class="{active: dropdown}" @click.stop="toggleDropdown">
                <div class="text" v-text="userName">
                </div>
                <div class="icon">
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div class="navbar-menu">
            <div class="wrapper" :class="{active: dropdownMenu}" @click="dropMenu">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </div>
            <div class="menu-dropdown" :class="{active: dropdownMenu}">
                <a class="item-menu" href="/about">About</a>
                <a class="item-menu" href="/contact-us">Contact</a>
                <a v-if="!user" class="item-menu" href="/login">Login</a>
                <a v-if="user" class="item-menu" href="{{ optional(auth()->user())->can('manage') ? url('admin#/dashboard') : url('dashboard') }}">Dashboard</a>
                <a v-if="user" class="item-menu" @click="navbar.account = !navbar.account">My Account</a>
                <a v-if="user" class="item-menu" @click="logout">Log Out</a>
            </div>
        </div>
        <div id="user-dropdown" class="dropdown" :class="{show: dropdown}">
            <a href="{{ optional(auth()->user())->can('manage') ? url('admin#/dashboard') : url('dashboard') }}"><div class="item" >Dashboard</div></a>
            <div class="item" @click="navbar.account = !navbar.account">My Account</div>
            <div class="item no-border" @click="logout">Log Out</div>
        </div>
    </div>
</div>
<transition name="fade" mode="out-in">
    <modal-account v-if="navbar.account" :user="$store.user" @close="navbar.account = false" @password="navbar.account = false; navbar.password = true;"></modal-account>
    <modal-password v-if="navbar.password" :user="$store.user" @close="navbar.password = false"></modal-password>
</transition>

@push('scripts')
    <script>
        window.__Data = {
            user: {!! auth()->id() ? auth()->user() : '0' !!},
        }

        if (window.__Data.user === 0) {
            window.__Data.user === undefined;
        }
    </script>
@endpush
