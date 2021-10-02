<template>
    <v-card outlined>
        <v-toolbar>
            <v-card-title>{{ $ll($lang.text.login.title) }}</v-card-title>
        </v-toolbar>
        <!--            <v-form ref="loginForm" class="px-5">-->
        <!--                <v-text-field-->
        <!--                    :label="$ll($lang.text.login.email)"-->
        <!--                    v-model="email"-->
        <!--                    :disabled="loading"-->
        <!--                    :rules="validations.email"-->
        <!--                    :counter="MAX_STRING_LENGTH"-->
        <!--                    hide-details="auto"-->
        <!--                    class="mt-8 mb-4" />-->

        <!--                <v-text-field-->
        <!--                    :label="$ll($lang.text.login.password)"-->
        <!--                    v-model="password"-->
        <!--                    :disabled="loading"-->
        <!--                    :rules="validations.password"-->
        <!--                    :counter="MAX_STRING_LENGTH"-->
        <!--                    hide-details="auto"-->
        <!--                    class="my-4" />-->
        <!--            </v-form>-->
        <v-card-actions>
            <v-dialog v-model="logoutConfirmation" max-width="290">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn text v-bind="attrs" v-on="on">{{ $ll($lang.text.login.logout_button) }}</v-btn>
                </template>
                <v-card>
                    <v-card-title class="text-h5">{{ $ll($lang.text.login.logout_title) }}</v-card-title>
                    <v-card-text>{{ $ll($lang.text.login.logout_confirm) }}</v-card-text>
                    <v-card-actions>
                        <v-btn text @click="logout()">{{ $ll($lang.text.general.yes) }}</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn text @click="logoutConfirmation = false">{{ $ll($lang.text.general.cancel) }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-spacer></v-spacer>
            <v-btn text @click="close()">{{ $ll($lang.text.general.close) }}</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script lang="ts">
import Vue from 'vue';
import {MAX_STRING_LENGTH, Validations} from "../../utils/ValidationRules";
import GetLoginUrl from "../../../domain/user/usecase/GetLoginUrl";
import Logout from "../../../domain/user/usecase/Logout";
import {AnonymousUser} from "../../../domain/common/model/User";

export default Vue.extend({
    props: {
        close: {
            type: Function,
            required: true
        }
    },
    data: function() {
        return {
            logoutConfirmation: false,
            email: "",
            password: "",
            MAX_STRING_LENGTH: MAX_STRING_LENGTH,
            validations: {
                email: Validations.email,
                password: Validations.password,
            }
        }
    },
    methods: {
        logout: function() {
            Logout();
            this.logoutConfirmation = false;
            this.$local.user = AnonymousUser;
        },
    }
});
</script>
