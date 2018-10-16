<template>
    <!-- footer -->
    <div id="logout-popup" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel2">Продление сессии</h4>
                </div>
                <div class="modal-body">
                    <h3>Сессия будет завершена через <span class="counter"></span> секунд.</h3>
                    <h5>Вы желаете продлить сессию?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeSession()" class="btn btn-default">Выйти</button>
                    <button type="button" @click="prolongSession()" class="btn btn-success">Продлить</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /footer -->
</template>

<script>
    import {store} from '../../store'
    import {AUTH_PREFIX} from '../../config/api'

    export default {
        http: {
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        },

        methods: {
            closeSession: function () {
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
            },

            prolongSession: function () {
                this.$http.post(AUTH_PREFIX + '/session/prolong', {token: store.getters.token}).then((response) => {
                    console.log(response);

                    if (response.body.success) {
                        console.log(response.body.message);
                    }

                    if (response.body.success && response.body.refreshedToken !== undefined) {
                        store.getters.timer.stop();
                        $('#logout-popup').modal('hide');
                        store.commit('token', response.body.refreshedToken);
                        return this.$parent.$options.methods.setUpToken(response.body.refreshedToken);
                    }
                }, (response) => {
                    console.log(response);

                    if (response.body.success !== true) {
                        console.log(response.body.message);
                    }
                });
            },
        }
    }

</script>