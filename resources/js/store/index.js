import Vue from 'vue'
import Vuex from 'vuex'
import Timer from 'easytimer.js'

Vue.use(Vuex);

export const SHOW_POPUP_TIME = 30000; // in milliseconds
export const DEFAULT_SHOW_POPUP_TIME = 30; // in seconds

export const store = new Vuex.Store({
    state: {
        disableForm: false,
        loading: true,
        token: null,
        timer: new Timer(),
    },
    mutations: {
        loading(state, value) {
            state.loading = value;
        },
        token(state, value) {
            state.token = value;
        },
        disableForm(state, value) {
            state.disableForm = value;
            $('input').attr('disabled', value);
        }
    },
    actions: {},
    getters: {
        isLoading: state => {
            return state.loading
        },
        token: state => {
            return state.token
        },
        timer: state => {
            return state.timer
        },
        isDisabledForm: state => {
            return state.disableForm
        }
    }
});