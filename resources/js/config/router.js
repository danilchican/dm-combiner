import {store} from '../store'

import Router from 'vue-router'
import Vue from 'vue'

import App from '../components/App'
import Home from '../components/Home'

Vue.use(Router);

export const SIDEBAR_POSITION = 'sidebar';
export const HEADER_POSITION = 'header';
export const NO_POSITION = 'no-position';

const router = new Router({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/dashboard',
            component: App,
            meta: {
                title: 'Админ-панель',
                iconCode: 'fa-home',
                position: SIDEBAR_POSITION
            },
            children: [
                {
                    path: '',
                    component: Home,
                    name: 'home',
                    meta: {
                        title: 'Главная',
                        iconCode: 'fa-home',
                        position: SIDEBAR_POSITION
                    }
                }
            ]
        }
    ]
});

router.beforeEach((to, from, next) => {
    store.commit('loading', true); // shows loading spinner

    let title = to.matched.filter(r => r.meta && r.meta.title)
        .map(r => r.meta.title)
        .join(' | ');

    if (title) {
        document.title = title;
    }

    next();
});

router.afterEach(() => {
    store.commit('loading', false); // hides loading spinner
});

export default router