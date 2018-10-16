<template>
    <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <router-link :to="{ name: menuItems[0].name }" class="site_title">
                    <i class="fa fa-mortar-board"></i> <span>Админ-панель</span>
                </router-link>
            </div>

            <div class="clearfix"></div>

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                <div class="menu_section">
                    <ul class="nav side-menu">
                        <template v-for="menuItem in menuItems">
                            <li v-if="menuItem.children !== undefined && menuItem.children.length > 0">
                                <a><i :class="setIconCode(menuItem.meta.iconCode)"></i> {{ menuItem.meta.title }} <span
                                        class="fa fa-chevron-down"></span></a>

                                <ul class="nav child_menu">
                                    <template v-for="subMenuItem in menuItem.children">
                                        <router-link tag="li" :to="{ name: subMenuItem.name }">
                                            <a>{{ subMenuItem.meta.title }}</a>
                                        </router-link>
                                    </template>
                                </ul>
                            </li>
                            <router-link v-else tag="li" :to="{ name: menuItem.name }">
                                <a><i :class="setIconCode(menuItem.meta.iconCode)"></i> {{ menuItem.meta.title }}</a>
                            </router-link>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- /sidebar menu -->
        </div>
    </div>
</template>

<script>
    export default {
        props: ['menuItems'],
        methods: {
            setIconCode: function (iconCode) {
                return this.$parent.$options.methods.setIconCode(iconCode);
            }
        }
    }
</script>