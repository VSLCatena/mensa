<template>
  <v-card outlined>
    <v-toolbar>
      <v-card-title>{{ $ll($lang.text.login.title) }}</v-card-title>
    </v-toolbar>
    <v-card-text class="mt-3">
      {{ $ll($lang.text.login.login_info) }}
      <br><br>
      <v-btn
        :loading="loading"
        color="secondary"
        @click="login()"
      >
        {{ $ll($lang.text.login.login_button) }}
      </v-btn>
    </v-card-text>
    <v-card-actions>
      <v-btn
        text
        @click="forgotPassword()"
      >
        {{ $ll($lang.text.login.forgot_password) }}
      </v-btn>
      <v-spacer />
      <v-btn
        text
        @click="close()"
      >
        {{ $ll($lang.text.general.close) }}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
  import Vue from 'vue';
  import {MAX_STRING_LENGTH} from "../../utils/ValidationRules";
  import {GetLoginUrl} from "../../../domain/user/usecase/GetLoginUrl";
  import {Config} from "../../../Config";

  export default Vue.extend({
    services: {
      getLoginUrl: GetLoginUrl
    },
    props: {
      close: {
        type: Function,
        required: true
      },
      isOpen: Boolean
    },
    data: function () {
      return {
        loading: false,
        loginUrl: null as string | null,
        email: "",
        password: "",
        MAX_STRING_LENGTH: MAX_STRING_LENGTH
      }
    },
    mounted: function () {
      this.loginUrl = null;
      this.loading = true;
      (this.$services.getLoginUrl as GetLoginUrl).execute()
        .then(value => {
          this.loading = false;
          this.loginUrl = value;
        });
    },
    methods: {
      login: function () {
        let loginUrl = this.loginUrl;
        if (loginUrl == null) return;

        // @ts-ignore
        window.location.href = loginUrl;
      },
      forgotPassword: function () {
        // @ts-ignore
        window.location.href = Config.changePasswordLink as string;
      }
    }
  });
</script>
