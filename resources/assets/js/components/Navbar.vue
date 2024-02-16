<template>
<div class="wrapper">
    <div class="container">
        <div class="logo" :class="{admin: isAdmin || width < 880}">
            <img :src="isAdmin || width < 880 ? '/images/PDFGlue_Logo_Blue.png' :  '/images/PDFGlue_Logo_White.png'">
        </div>
        <div v-if="!isAdmin" class="links">
            <a class="link" href="/about">
                <div class="text">
                    <p>About</p>
                </div>
            </a>
            <a class="link" href="/contact-us">
                <div class="text">
                    <p>Contact</p>
                </div>
            </a>
        </div>

        <div v-if="isAdmin" class="links" :class="{admin: isAdmin}">
            <router-link tag="a" to="/manage-files" class="link" ref="link" :class="{small: display.small}">
                <div class="icon">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                </div>
                <div class="text">
                    <p>File Management</p>
                </div>
            </router-link>
            <router-link tag="a" to="/manage-users" class="link" :class="{small: display.small}">
                <div class="icon">
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                </div>
                <div class="text">
                    <p>Pdfglue Users</p>
                </div>
            </router-link>
            <router-link tag="a" to="/dashboard" class="link" :class="{small: display.small}">
                <div class="icon">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </div>
                <div class="text">
                    <p>Create New Document</p>
                </div>
            </router-link>
            <router-link tag="a" to="#" class="link" :class="{small: display.small}">
                <!-- <div class="icon">
                    <i class="fa fa-date" aria-hidden="true"></i>
                </div> -->
                <div class="text" style="color:red;text-align: center;">
                     
                    <p><small>Last Update</small><br/> 12/12/2022</p>
                </div>
            </router-link>
            
        </div>

        <div class="user">
            <div class="account" :class="{active: dropdown}" @click.stop="toggleDropdown">

                <div class="text">
                    {{userName}}
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
            <div v-if="!isAdmin" class="menu-dropdown" :class="{active: dropdownMenu}">
                <a class="item-menu" href="/about">About</a>
                <a class="item-menu" href="/contact-us">Contact</a>
                <a class="item-menu" @click="navbar.account = !navbar.account">My Account</a>
                <a class="item-menu" @click="logout">Log Out</a>
            </div>
            <div v-if="isAdmin" class="menu-dropdown" :class="{active: dropdownMenu}">
                <router-link tag="a" to="/manage-files" class="item-menu" @click.native="closeMenu" >File Management</router-link>
                <router-link tag="a" to="/manage-users" class="item-menu" @click.native="closeMenu" >Pdfglue Users</router-link>
                <router-link tag="a" to="/dashboard" class="item-menu" @click.native="closeMenu" >Create New Document</router-link>
                <a class="item-menu" @click="navbar.account = !navbar.account">My Account</a>
                <router-link tag="a" to="/timeout" class="item-menu" @click.native="closeMenu">Set Timeout</router-link>
                <a class="item-menu" @click="logout">Log Out</a>
            </div>
        </div>


    </div>

    <div id="user-dropdown" class="dropdown" :class="{show: dropdown}">
        <div class="item" @click="navbar.account = !navbar.account">My Account</div>
        <router-link v-if="isAdmin" tag="div" to="/timeout" class="item" @click.native="toggleDropdown">Set Timeout</router-link>
        <div class="item" @click="logout">Log Out</div>
    </div>
    <transition name="fade" mode="out-in">
        <modal-account v-if="navbar.account" :user="$store.state.user" @close="navbar.account = false" @password="navbar.account = false; navbar.password = true;"></modal-account>
        <modal-password v-if="navbar.password" :user="$store.state.user" @close="navbar.password = false"></modal-password>
    </transition>
</div>
</template>

<script>
import ModalAccount from './modals/Modal-myAccount.vue'
import ModalPassword from './modals/Modal-changePass.vue'
import ModalLogin from './modals/Modal-login.vue'
import ModalRegister from './modals/Modal-register.vue'

export default {
    props: {
        active: {
            type: String,
            default: 'admin',
        }
    },
    components: {
        ModalAccount: require('./modals/Modal-myAccount.vue'),
        ModalPassword: require('./modals/Modal-changePass.vue'),
    },
    data() {
        return {
            display: {
                small: false,
            },
            dropdown: false,
            navbar: {
                account: false,
                password: false,
                loginModal: false,
                registerModal: false,
                errors: {email:''}
            },
            dropdownMenu: false,
            width: 0,
        }
    },
    mounted() {
        this.resize();
        this.width = document.body.clientWidth;
        window.addEventListener('resize', this.resize);

    },
    computed: {
        isAdmin() {
            return !!this.$store.state.user.admin;
        },
        userName() {
            return this.$store.state.user.name;
        }
    },
    methods: {
        dropMenu() {
            this.dropdownMenu = !this.dropdownMenu;
        },
        closeMenu() {
            this.dropdownMenu = false;
            //console.log('asdada')
        },
        resize() {
            if (this.isAdmin && parseInt(window.getComputedStyle(this.$refs.link.$el)['padding-left']) < 21) {
                this.display.small = true;
            } else {
                this.display.small = false;
            }
            this.width = document.body.clientWidth;
        },
        isActive(route) {
            return route === this.active;
        },
        navbarOutside(ev) {
            if (window.event.target.id !== 'user-dropdown' && window.event.target.parentNode.id !== 'user-dropdown') {
                this.toggleDropdown();
            }
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
        logout() {
            axios.post('/logout').then( () => window.location.href="/");
        },
        navigate(route) {
            window.location.href=route;
        }
    }
}
</script>

<style lang="scss" scoped>
@import "resources/assets/sass/variables";
@import "resources/assets/sass/mixins";

.wrapper {
    z-index: 2;

    .container {

        width: 100%;
        @media (max-width: 1260px) {
            width: calc(100%);
        }
        z-index: 1;
        background: #fff;
        height: $navbar-height;
        @include flex;
        @include flex-direction-row;
        @include fine-border(#d4d5d6, 'bottom');
        // @include drop-shadow(0px, 0px, 6px, 2px, rgba(0,0,0,0.1));
        //
        // @include box-shadow(0 3px 5px -1px rgba(0,0,0,.04), 0 6px 10px rgba(0,0,0,.03), 0 1px 18px rgba(0,0,0,.02));
        @include basic-box-shadow;
        @media screen and (max-width: 1024px) {
            height: 50px;
        }
        .logo {
            @include flex;
            @include justify-content-center;
            @include flex-direction-column;
            @include align-items(center);
            min-width: $navbar-logo-width;

            cursor: pointer;

            background: $color-primary;

            &.admin {

                background: none;

                .text {
                    color: $color-primary;
                }

            }

            .text {
                text-align: center;
                color: #fff;
                font-size: 26px;

                .start {
                    text-transform: uppercase;
                    font-weight: 800;
                }

                .end {
                    text-transform: capitalize;
                    font-weight: 600;
                }
            }

            img {
                width: 110px;
            }

            @media(max-width: 1024px) and (min-width: 880px) {

                // min-width: auto;
                display: none;
            }

            @media (max-width: 1024px) {
                margin-left: -78px;
            }

            @media (max-width: 738px) {
                margin-left: -112px;
                img {
                    width: 90px;
                }
            }


        }

        .links {
            @include flex;
            @include flex-grow(1);
            padding-left: 50px;

            .link {
                text-decoration: none;
                @include flex;
                @include justify-content-center;
                padding-left: 40px;
                padding-right: 40px;

                color: $color-text-secondary;
                font-weight: 600;
                font-size: $font-size;
                position: relative;

                cursor: pointer;
                @include transition(color, 0.15s, ease-in-out);

                &.small {
                    .text {
                        margin-left: -5px;
                        margin-right: -5px;

                    }
                    .icon {
                        display: none;
                    }
                }

                .icon {
                    @include flex;
                    @include justify-content-center;
                    @include flex-direction-column;

                    width: 27px;
                    font-size: $font-size + 6px;
                    text-align: left;
                }

                .text {
                    @include flex;
                    @include justify-content-center;
                    @include flex-direction-column;
                    text-align: left;
                }
                &:hover {

                    color: $color-primary;

                    &::after {
                        width: calc(100% - 80px);
                    }
                }

                &::after {
                    content: '';
                    height: 2px;
                    width: 0;
                    left: 40px;
                    position: absolute;
                    background: $color-primary;
                    top: 70%;
                    transform: translateY(-50%);
                    @include transition(width, 0.15s, ease-in-out);

                }

            }

            &.admin {
                padding-left: 0;

                .link {

                    padding-left: 3.3%;
                    padding-right: 3.3%;
                    @include fine-border(#d4d5d6, 'left');
                    &:last-child {
                        @include fine-border(#d4d5d6, 'right');
                    }

                    &::after {
                        content: '';
                        position: absolute;
                        left: 50%;
                        height: 2px;
                        width: 0;
                        top: auto;
                        bottom: 0;
                        transform: none;
                        background: $color-primary;
                        @include transition(all, 0.15s, ease-in-out);
                    }

                    &.router-link-active {
                        color: $color-primary;
                        &::after {
                            left: 0;
                            width: 100%;
                        }
                    }
                }
            }

            @media (max-width: 879px) {
                display: none;
            }
        }

        .user {
            @include flex;
            @include flex-direction-row;
            @include justify-content-end;

            min-width: $navbar-user-width;
            padding-right: 40px;

            .account {
                @include flex;
                @include justify-content-center;
                @include flex-direction-row;
                font-weight: 600;
                font-size: 18px;
                color: #383838;
                background: #fff;

                cursor: pointer;
                &:hover {
                    color: $color-primary;
                }

                &.active {

                    color: $color-primary;

                }

                .text {
                    @include flex;
                    @include flex-grow(1);
                    @include justify-content-center;
                    @include flex-direction-column;
                    margin-right: 10px;
                }

                .icon {
                    @include flex;
                    @include flex-grow(1);
                    @include justify-content-center;
                    @include flex-direction-column;
                    padding-top: 3px;
                }

            }

            @media(max-width: 1024px) {

                min-width: auto;
            }

            @media (max-width: 879px) {
                display: none;
            }



        }

        .navbar-menu {
            z-index: 2;
            display: none;
            box-shadow: none;
            font-size: 24px;
            position: relative;
            margin-right: 43px;
            @include flex-grow(1);
            @include flex-direction-row;
            @include justify-content-end;

            .wrapper {
                @include flex;
                @include flex-direction-column;
                @include justify-content-center;
                cursor: pointer;
                color: $color-primary;
                &:hover, &.active {
                    color: $color-primary;
                }
            }
            .menu-dropdown {
                // display: none;
                pointer-events: none;
                opacity: 0;
                z-index: 0;
                position: absolute;
                // right: -36px;
                top: $navbar-height;
                width: 100vw;
                right: -43px;
                @include accent-box-shadow;
                @include box-sizing(border-box);
                @include fine-border;
                @include transition(all .1s ease-in-out);
                background: #fff;
                .item-menu {
                    text-decoration: none;
                    position: relative;
                    display: block;
                    padding-left: 25px;
                    padding-right: 25px;
                    padding-top: 15px;
                    padding-bottom: 15px;
                    color: #01050d;

                    &:hover {
                        color: $color-primary;
                    }
                    &::after {
                        position: absolute;
                        content: '';
                        height: 1px;
                        background: #b3b3b3;
                        width: calc(102% - 85px);
                        bottom: 0;
                        left: 25px;
                        right: 25px;
                    }

                    &:last-child {
                        &::after {
                            display: none;
                        }
                    }
                }
                &.active {
                    pointer-events:all;
                    opacity: 1;
                    // display: block;
                }

                @media (max-width: 738px) {
                    top: 50px;
                    right: -17px;
                    // pad
                }
                &:last-child {
                    padding-bottom: 5px;
                }

                @media (max-width: 879px) {
                    font-size: 18px;
                    top: 50px;
                }
            }

            @media (max-width: 879px) {
                @include flex;
            }

            @media (max-width: 738px) {
                margin-right: 17px;
            }


        }

    }

    .dropdown {
        position: absolute;
        background: #fff;
        top: $navbar-height - 10px;
        transform: translateY(calc(-90% - 10px));
        min-width: 200px;
        right: 30px;
        border-radius: 8px;
        padding-top: 15px;
        padding-bottom: 5px;
        opacity: 0;
        z-index: -1;
        // @include drop-shadow(0px, 0px, 8px, 2px, rgba(0,0,0, 0.2));
        @include context-box-shadow;
        @include transition(transform .10s ease-in-out, opacity .10s ease-in-out);

        &.show {

            transform: translateY(calc(0%));
            opacity: 1;
            @include transition(transform .07s ease-in-out, opacity .10s ease-in-out);
        }

        .item {
            font-size: 16px;
            color: $color-text-secondary;
            font-weight: 600;
            position: relative;
            text-align: right;
            cursor: pointer;
            padding: 12px 25px;
            &:hover {
                color: $color-primary;
            }
            &:first-child::after, &:nth-child(2)::after  {
                position: absolute;
                content: '';
                height: 1px;
                background: #b3b3b3;
                width: calc(102% - 50px);
                bottom: 0;
                left: 25px;
            }
        }
        @media(max-width: 1024px) {
            top: 40px;
        }

        @media(max-width: 738px) {
            // top: $navbar-height - 10px;
        }

    }
}
</style>
