<template>
    <!-- top navigation -->
    <div class="top_nav">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                           aria-expanded="false">
                            <img src="/images/img.jpg" alt="">John Doe
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                            <template v-for="menuItem in menuItems">
                                <router-link tag="li" :to="{ name: menuItem.name }">
                                    <a>
                                        <i :class="setIconCode(menuItem.meta.iconCode)"></i>
                                        {{ menuItem.meta.title }}
                                    </a>
                                </router-link>
                            </template>
                            <li><a @click="closeSession()"><i class="fa fa-sign-out pull-right"></i> Выйти</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- /top navigation -->
</template>

<script>
    import {store} from '../../store'
    import {API_PREFIX, AUTH_PREFIX} from "../../config/api";

    export default {
        props: ['menuItems'],

        data() {
            return {
            }
        },

        http: {
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        },

        methods: {
            setIconCode(iconCode) {
                return this.$parent.$options.methods.setIconCode(iconCode);
            },

            closeSession() {
                this.$http.post(AUTH_PREFIX + '/logout', {token: store.getters.token}).then((response) => {
                    console.log(response);
                    store.commit('token', null);

                    if (response.body.success) {
                        console.log(response.body.message);
                    }

                    location.href = "/";
                }, (response) => {
                    console.log(response);

                    if (response.body.success !== true) {
                        console.log(response.body.message);
                    }
                });
            }
        }
    }
</script>