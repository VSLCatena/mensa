<template>
  <div class="container mt-3 mb-3">
    <h2>{{ $ll($lang.text.faq.title) }}</h2>

    <div class="mt-4" v-if="loading">
      <v-skeleton-loader v-for="x in 5" :key="x" type="list-item-two-line" class="mt-1"/>
    </div>
    <div v-else>
      <v-expansion-panels focusable v-model="openedItem" class="mt-4">
        <FaqItem
          v-for="faq in this.faqs" :key="faq.id"
          :faq="faq"/>
      </v-expansion-panels>

      <div class="text-right">
        <v-btn class="mt-3 pa-2 add-button" outlined v-if="$local.user.isAdmin" @click="onAddItemClicked()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {GetFaqs} from "../../../domain/faq/usecase/GetFaqs";
  import Vue from 'vue';
  import {Faq} from "../../../domain/faq/model/Faq";
  import FaqItem from "./components/FaqItem.vue";

  export default Vue.extend({
    components: {FaqItem},
    services: {
      getFaqs: GetFaqs
    },
    data: function () {
      return {
        loading: false,
        openedItem: null as Number | null,
        faqs: null as Faq[] | null
      }
    },
    methods: {
      retrieveFaqs: function () {
        this.loading = true;

        (this.$services.getFaqs as GetFaqs).execute()
          .then(faqs => {
            this.faqs = [...faqs];
            this.loading = false;
          })
          .catch(error => {
            console.error(error);
            this.loading = false;
          })
      },
      onAddItemClicked: function () {

      }
    },
    created() {
      this.retrieveFaqs()
    }
  });
</script>
