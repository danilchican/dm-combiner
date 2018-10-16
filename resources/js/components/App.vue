<template>
    <div class="container body">
        <nprogress-container/>
        <div class="main_container">
            <header-component :menu-items="headerMenuItems" ref="header"/>
            <sidebar-component :menu-items="sidebarMenuItems"/>
            <!-- page content -->
            <div class="right_col" role="main">
                <router-view :key="$route.fullPath"/>
            </div>
            <!-- /page content -->
            <footer-component ref="footer"/>
        </div>
    </div>
</template>

<script>
    import Header from './partials/Header'
    import Sidebar from './partials/Sidebar'
    import Footer from './partials/Footer'
    import NprogressContainer from 'vue-nprogress/src/NprogressContainer'
    import {SIDEBAR_POSITION, HEADER_POSITION} from '../config/router'
    import {store, SHOW_POPUP_TIME, DEFAULT_SHOW_POPUP_TIME} from '../store'

    export default {
        data() {
            return {
                sidebarMenuItems: [],
                headerMenuItems: []
            }
        },

        created() {
            this.$nprogress.start();
            this.setUpToken();
            this.fillSidebarMenuItems();
            this.fillHeaderMenuItems();
            this.$nprogress.done();
        },

        methods: {

            /**
             * Fetch routes related to the sidebar
             * and populate menu items.
             */
            fillSidebarMenuItems() {
                let menuItems = this.filterRoutesByPosition(SIDEBAR_POSITION);

                menuItems.forEach(route => {
                    let populatedMenuItem = this.populateMenuItem(route);
                    this.sidebarMenuItems.push(populatedMenuItem);
                });
            },

            /**
             * Fetch routes related to the header
             * and populate menu items.
             */
            fillHeaderMenuItems() {
                let menuItems = this.filterRoutesByPosition(HEADER_POSITION);

                menuItems.forEach(route => {
                    let populatedMenuItem = this.populateMenuItem(route);
                    this.headerMenuItems.push(populatedMenuItem);
                });
            },

            /**
             * Filter all routes by position.
             *
             * @param position
             * @returns {RouteConfig[]}
             */
            filterRoutesByPosition(position) {
                let routes = this.fetchRoutes(this.$router.options.routes[0].children);

                return routes.filter(r => r.children
                    ? this.checkRouteInfo(r, position)
                    && (r.children = r.children.filter(cr => this.checkRouteInfo(cr, position)))
                    : this.checkRouteInfo(r, position)
                )
            },

            checkRouteInfo(route, position) {
                return route.meta && route.meta.position && route.meta.position === position;
            },

            /**
             * Populate date of a menu item.
             *
             * @param route
             * @returns {{meta, name}}
             */
            populateMenuItem(route) {
                return {
                    meta: route.meta,
                    name: route.name,
                    children: route.children
                }
            },

            /**
             * Fetch child routes from particular route.
             *
             * @param route
             * @returns {Array}
             */
            fetchRoutes(route) {
                let routes = [];

                if (route instanceof Array) {
                    for (let i = 0; i < route.length; i++) {
                        routes = routes.concat(this.fetchRoutes(route[i]));
                    }
                } else {
                    routes.push(route);
                }

                return routes;
            },

            /**
             * Set icon code class for menu item.
             *
             * @param iconCode
             * @returns {string}
             */
            setIconCode: function (iconCode) {
                return iconCode !== undefined ? 'fa ' + iconCode : '';
            },

            setUpToken: function (refreshedToken) {
                console.log('set up token');
                const currentApp = this;

                let token = refreshedToken !== undefined
                    ? refreshedToken
                    : $("meta[name='auth-token']").attr("content");

                let time = new Date(jwt_decode(token).exp * 1000).getTime()
                    - new Date(Date.now()).getTime()
                    - SHOW_POPUP_TIME - 5000;

                Vue.http.headers.common['Authorization'] = 'Bearer ' + token;
                store.commit('token', token);

                if (time > 0) {
                    setTimeout(function () {
                        currentApp.showPopUp(true);
                    }, time);
                } else {
                    currentApp.showPopUp(false, time);
                }
            },

            showPopUp: function (useTimeout, time) {
                let timer = store.getters.timer;

                if (useTimeout) {
                    timer.start({countdown: true, startValues: {seconds: DEFAULT_SHOW_POPUP_TIME}});
                } else {
                    let t = Math.round((time + SHOW_POPUP_TIME) / 1000);
                    timer.start({countdown: true, startValues: {seconds: t}});
                }

                this.updateTimer(timer);
                $('#logout-popup').modal('show');

                this.setTimerEvents(timer, this);
            },

            setTimerEvents: function (timer, currentApp) {
                timer.addEventListener('secondsUpdated', function () {
                    currentApp.updateTimer(timer);
                });
                timer.addEventListener('targetAchieved', function () {
                    currentApp.updateTimer(timer);
                    currentApp.$refs.footer.closeSession();
                });
            },

            updateTimer: function (timer) {
                $('#logout-popup .modal-body .counter').html(timer.getTimeValues().seconds);
            },
        },

        components: {
            'header-component': Header,
            'sidebar-component': Sidebar,
            'footer-component': Footer,
            'nprogress-container': NprogressContainer
        }
    }
</script>