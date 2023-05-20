<template>
  <v-expansion-panel :value="faq.id">
    <v-expansion-panel-header>
      <div
        v-if="inEditMode"
        class="handle pa-2 flex-grow-0"
      >
        <v-icon>mdi-menu</v-icon>
      </div>
      <h4>{{ faq.question }}</h4>
      <div
        v-if="onEditClicked !== undefined"
        class="flex-grow-0"
      >
        <v-btn
          class="pa-2 add-button"
          icon
          @click="onEditClicked(faq)"
        >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </div>
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
      onEditClicked: {
        type: Function,
        required: false,
        default: undefined
      },
      inEditMode: {
        type: Boolean,
        required: false,
        default: false
      }
    }
  });
</script>
