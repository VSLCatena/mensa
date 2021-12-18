<template>
    <v-card :loading="!errored">
        <div class="pa-4">
            {{ errored ? $ll($lang.text.login.error) : $ll($lang.text.login.logging_in) }}
        </div>
    </v-card>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Login from "../../../domain/user/usecase/Login";
    import GetSelf from "../../../domain/user/usecase/GetSelf";
    import {AnonymousUser} from "../../../domain/common/model/User";
    import Logout from "../../../domain/user/usecase/Logout";

    const Anonymous = AnonymousUser;

    export default Vue.extend({
        data: function () {
            return {
                errored: false
            }
        },
        mounted() {
            let code = new URLSearchParams(window.location.search).get("code") as string;
            if (code == null) return;
            Login(code)
                .then(() => GetSelf())
                .then(user => {
                    this.$local.user = user;
                    this.$router.replace('/');
                })
                .catch(() => {
                    Logout();
                    this.$local.user = Anonymous;
                    this.errored = true;
                });
        }
    });
</script>
