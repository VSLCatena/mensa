<template>
  <v-dialog
    v-model="isOpen"
    max-width="800"
    transition="dialog-bottom-transition"
  >
    <ProfileView
      v-if="isLoggedIn"
      :is-open="isOpen && isLoggedIn"
      :close="close"
    />
    <LoginView
      v-else
      :is-open="isOpen && !isLoggedIn"
      :close="close"
    />
  </v-dialog>
</template>

<script lang="ts">
  import Vue from 'vue';
  import LoginView from "./LoginView.vue";
  import ProfileView from "./ProfileView.vue";
  import {AnonymousUser} from "../../../domain/common/model/User";

  export default Vue.extend({
    components: {ProfileView, LoginView},
    data: function () {
      return {
        isOpen: false,
      }
    },
    computed: {
      isLoggedIn: function (): boolean {
        return this.$local.user != AnonymousUser
      }
    },
    methods: {
      open: function () {
        this.isOpen = true;
      },
      close: function () {
        this.isOpen = false;
      }
    }
  });
</script>
