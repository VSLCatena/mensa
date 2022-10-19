<template>
  <v-expansion-panel>
    <v-expansion-panel-header>
      <h4>{{ faq.question }}</h4>
    </v-expansion-panel-header>
    <v-expansion-panel-content>
      <div class="pt-3">
        {{ faq.answer }}
      </div>
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script lang="ts">
  import Vue, {PropType} from 'vue';
  import {Faq} from "../../../../domain/faq/model/Faq";

  export default Vue.extend({
    props: {
      faq: {
        type: Object as PropType<Faq>,
        required: true
      },
    }
  });
</script>
