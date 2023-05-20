<template>
  <div class="container mt-3 mb-3">
    <MensaSignupDialog ref="mensaSignupDialog" />
    <MensaOverviewDialog ref="mensaOverviewDialog" />
    <MensaEditDialog ref="mensaEditDialog" />
    <v-banner>
      <div class="d-flex">
        <div>
          <span class="text--secondary">{{ $ll($lang.text.mensa.mensas_between) }}</span> {{ startDate }}
          <span class="text--secondary">{{ $ll($lang.text.and) }}</span> {{ endDate }}
        </div>
        <v-spacer />
        <div>
          <v-btn
            :loading="loading"
            class="mr-4"
            @click="offsetWeeks(-2)"
          >
            {{ $ll($lang.text.mensa.previous_weeks) }}
          </v-btn>
          <v-btn
            :loading="loading"
            @click="offsetWeeks(2)"
          >
            {{ $ll($lang.text.mensa.next_weeks) }}
          </v-btn>
        </div>
      </div>
    </v-banner>

    <div
      v-if="loading"
      class="mt-4"
    >
      <v-skeleton-loader
        v-for="x in 5"
        :key="x"
        type="list-item-two-line"
        class="mt-1"
      />
    </div>

    <v-expansion-panels
      v-else
      v-model="openedItem"
      focusable
      accordion
      class="mt-4"
    >
      <MensaItem
        v-for="mensa in mensas"
        :key="mensa.id"
        :mensa="mensa"
        :on-signup-clicked="onSignupMensaClicked"
        :on-overview-clicked="currentUser.isAdmin ? onMensaOverviewClicked : undefined"
        :on-edit-clicked="isLoggedIn(currentUser) ? onMensaEditClicked : undefined"
      />
    </v-expansion-panels>

    <div class="mt-4 d-flex">
      <v-spacer />
    </div>
  </div>
</template>

<script lang="ts">
  import {GetMensas} from "../../../domain/mensa/usecase/GetMensas";
  import {Mensa} from "../../../domain/mensa/model/Mensa";
  import Vue from 'vue';
  import MensaItem from './components/MensaItem.vue';
  import {Between} from "../../../domain/mensa/model/MensaList";
  import {formatDate} from "../../formatters/DateFormatter";
  import MensaSignupDialog from "./dialogs/signup/MensaSignupDialog.vue";
  import MensaOverviewDialog from "./dialogs/overview/MensaOverviewDialog.vue";
  import {AuthUser, isLoggedIn} from "../../../domain/common/model/User";
  import MensaEditDialog from "./dialogs/mensa/MensaEditDialog.vue";

  export default Vue.extend({
    components: {MensaEditDialog, MensaOverviewDialog, MensaSignupDialog, MensaItem},
    services: {
      getMensas: GetMensas
    },
    data: function () {
      return {
        openedItem: null,
        mensas: [] as Mensa[],
        weekOffset: -1,
        loading: true,
        between: null as Between | null,
        mensaSignup: null as Mensa | null,
      }
    },
    computed: {
      startDate: function (): string | null {
        let between = this.between;
        if (between == null) return null;
        return formatDate(between.start, {withTime: false});
      },
      endDate: function (): string | null {
        let between = this.between;
        if (between == null) return null;
        return formatDate(between.end, {withTime: false});
      },
      currentUser: function (): AuthUser {
        return this.$local.user;
      }
    },
    watch: {
      weekOffset: function (offset) {
        this.retrieveMensas(offset, true);
      },
      currentUser: function () {
        this.retrieveMensas(this.weekOffset, false);
      }
    },
    created() {
      this.weekOffset = 0;
    },
    methods: {
      isLoggedIn,
      offsetWeeks: function (offset: number) {
        this.weekOffset += offset;
      },
      onSignupMensaClicked: function (mensa: Mensa) {
        this.$refs.mensaSignupDialog.open(mensa);
      },
      onMensaOverviewClicked: function (mensa: Mensa) {
        this.$refs.mensaOverviewDialog.open(mensa);
      },
      onMensaEditClicked: function (mensa: Mensa) {
        this.$refs.mensaEditDialog.open(mensa);
      },
      retrieveMensas: function (offset: number, clearItem: boolean) {
        this.loading = true;

        (this.$services.getMensas as GetMensas).execute(offset)
          .then(mensaList => {
            if (clearItem) this.openedItem = null;
            this.mensas = [...mensaList.mensas];
            this.between = mensaList.between;
            this.loading = false;
          })
          .catch(error => {
            console.error(error);
            this.loading = false;
          })
      }
    }
  });
</script>
