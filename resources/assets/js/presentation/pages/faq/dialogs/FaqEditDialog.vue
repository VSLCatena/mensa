<template>
  <v-dialog
    v-model="isOpen"
    max-width="800"
    transition="dialog-bottom-transition"
    :persistent="loading"
  >
    <v-card>
      <v-toolbar>
        <v-card-title>{{ $ll(faq.id ? $lang.text.faq.dialog.edit.title.existing : $lang.text.faq.dialog.edit.title.new) }}</v-card-title>
      </v-toolbar>
      <div class="px-3 pt-3">
        <v-form ref="mensaEditForm">
          <v-text-field
            v-model="faq.question"
            :label="$ll($lang.text.faq.dialog.edit.question)"
            :disabled="loading"
            :rules="validations.question"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="mt-8 mb-4"
          />

          <v-text-field
            v-model="faq.answer"
            :label="$ll($lang.text.faq.dialog.edit.answer)"
            :disabled="loading"
            :rules="validations.answer"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="mt-8 mb-4"
          />
        </v-form>
      </div>
      <v-card-actions>
        <v-btn
          text
          :loading="loading"
          @click="close(); onSaveClicked(faq)"
        >
          {{ $ll($lang.text.general.save) }}
        </v-btn>
        <v-btn
          v-if="faq.id"
          text
          :loading="loading"
          @click="close(); onDeleteClicked(faq)"
        >
          {{ $ll($lang.text.general.delete) }}
        </v-btn>
        <v-spacer />
        <v-btn
          text
          :loading="loading"
          @click="close()"
        >
          {{ $ll($lang.text.general.close) }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import Vue from 'vue';
  import {MAX_STRING_LENGTH, Validations} from "../../../utils/ValidationRules";
  import {Faq} from "../../../../domain/faq/model/Faq";

  export type EditableFaqs = Omit<Faq, 'id'> & Partial<Pick<Faq, 'id'>>

  export default Vue.extend({
    props: {
      onSaveClicked: {
        type: Function,
        required: true
      },
      onDeleteClicked: {
        type: Function,
        required: true
      }
    },
    data: function () {
      return {
        isOpen: false,
        loading: false,
        MAX_STRING_LENGTH: MAX_STRING_LENGTH,
        validations: {
          question: [Validations.Required, Validations.MaxStringLengthValidation],
          answer: [Validations.Required, Validations.MaxStringLengthValidation],
        },
        faq: {
          id: undefined as string | undefined,
          question: "",
          answer: "",
        } as EditableFaqs
      }
    },
    methods: {
      open: function (faqItem?: EditableFaqs) {
        if (!this.$local.user.isAdmin) return;
        this.isOpen = true;
        this.faq = {
          question: '',
          answer: '',
          ...faqItem
        };
      },
      close: function () {
        this.isOpen = false;
      }
    }
  });
</script>
