<template>
  <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition" :persistent="loading">
    <v-card>
      <v-toolbar>
        <v-card-title>{{ $ll($lang.text.server_settings.title) }}</v-card-title>
        <template v-slot:extension>
          <v-tabs v-model="tab" show-arrows>
            <v-tabs-slider></v-tabs-slider>
            <v-tab :key="0">{{ $ll($lang.text.server_settings.tabs.general) }}</v-tab>
            <v-tab :key="1">{{ $ll($lang.text.server_settings.tabs.mensas) }}</v-tab>
            <v-tab :key="2">{{ $ll($lang.text.server_settings.tabs.faq) }}</v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
      <div class="px-3 pt-3">
        <v-form ref="mensaEditForm">
          <v-tabs-items v-model="tab">
            <v-tab-item :key="0" class="pa-5">
            </v-tab-item>
            <v-tab-item :key="1" class="pa-5">
            </v-tab-item>
            <v-tab-item :key="2" class="pa-5">
            </v-tab-item>
          </v-tabs-items>
        </v-form>
      </div>
      <v-card-actions>
        <v-btn text :loading="loading" @click="save()">{{ $ll($lang.text.general.save) }}</v-btn>
        <v-spacer></v-spacer>
        <v-btn text :loading="loading" @click="isOpen = false">{{ $ll($lang.text.general.close) }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import Vue from 'vue';

  export default Vue.extend({
    data: function () {
      return {
        isOpen: false,
        loading: false,
        tab: 0,
      }
    },
    methods: {
      open: function () {
        if (!this.$local.user.isAdmin) return;
        this.isOpen = true;
      },
      save: function () {

      },
    }
  });
</script>
