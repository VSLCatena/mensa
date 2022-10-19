<template>
  <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition">
    <v-card outlined v-if="mensa != null">
      <v-toolbar>
        <v-card-title>{{ $ll($lang.text.signup.signups_for) }} {{ formattedDate }}</v-card-title>
      </v-toolbar>
      <div class="px-3 pt-3">
        {{ $ll($lang.text.signup.signups_currently) }}<br/>
        <span class="text--secondary">{{ names }}</span>
      </div>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="isOpen = false">{{ $ll($lang.text.general.close) }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import Vue from 'vue';
  import {Mensa} from "../../../../../domain/mensa/model/Mensa";
  import {formatUsers} from "../../../../formatters/StringFormatter";
  import {AnonymousUser, IdentifiableUser} from "../../../../../domain/common/model/User";
  import {formatDate} from "../../../../formatters/DateFormatter";

  export default Vue.extend({
    data: function () {
      return {
        isOpen: false,
        mensa: null as Mensa | null,
      }
    },
    methods: {
      open: function (mensa: Mensa) {
        if (Number.isInteger(mensa.signups)) return;
        this.mensa = mensa;
        this.isOpen = true;
      },
    },
    computed: {
      names: function (): string {
        let signups = this.mensa?.signups;
        if (Number.isInteger(signups) || this.$local.user == AnonymousUser) return "";

        return formatUsers(signups as IdentifiableUser[]) ?? "";
      },
      formattedDate: function (): string | null {
        let mensa = this.mensa;
        if (mensa == null || this.$local.user == AnonymousUser) return null;

        return formatDate(mensa.date);
      },
    }
  });
</script>
