<template>
  <v-dialog
    v-model="isOpen"
    max-width="290"
  >
    <v-card>
      <v-card-title
        v-if="title !== undefined"
        class="text-h5"
      >
        {{ title }}
      </v-card-title>
      <v-card-text v-if="message !== undefined">
        {{ message }}
      </v-card-text>
      <v-card-text v-else>
        <slot />
      </v-card-text>
      <v-card-actions>
        <v-btn
          v-if="negativeButton !== undefined"
          text
          @click="onNegative()"
        >
          {{ negativeButton.text }}
        </v-btn>
        <v-spacer />
        <v-btn
          v-if="neutralButton !== undefined"
          text
          @click="onNeutral()"
        >
          {{ neutralButton.text }}
        </v-btn>
        <v-spacer />
        <v-btn
          v-if="positiveButton !== undefined"
          text
          @click="onPositive()"
        >
          {{ positiveButton.text }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import Vue, {PropType} from 'vue';

  export interface DialogButton {
    text: string,
    onClick: Function | undefined
  }

  export default Vue.extend({
    props: {
      title: {
        type: String,
        required: false,
        default: undefined
      },
      message: {
        type: String,
        required: false,
        default: undefined
      },
      positiveButton: {
        type: Object as PropType<DialogButton>,
        required: false,
        default: undefined
      },
      neutralButton: {
        type: Object as PropType<DialogButton>,
        required: false,
        default: undefined
      },
      negativeButton: {
        type: Object as PropType<DialogButton>,
        required: false,
        default: undefined
      },
    },
    data: function() {
      return {
          isOpen: false,
        };
    },
    methods: {
      open: function () {
        this.isOpen = true;
      },
      onPositive: function () {
        this.positiveButton?.onClick?.call(this);
        this.isOpen = false;
      },
      onNeutral: function () {
        this.neutralButton?.onClick?.call(this);
        this.isOpen = false;
      },
      onNegative: function () {
        this.negativeButton?.onClick?.call(this);
        this.isOpen = false;
      }
    }
  });
</script>
