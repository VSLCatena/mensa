<template>
  <div class="container mt-3 mb-3">
    <FaqEditDialog
      ref="faqEditDialog"
      :on-delete-clicked="onEditDialogDeleteClicked"
      :on-save-clicked="onEditDialogSaveClicked"
    />
    <div
      v-if="$local.user.isAdmin"
      class="float-right"
    >
      <v-btn
        v-if="!editMode"
        class="pa-2 add-button"
        outlined
        @click="onEditClicked()"
      >
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
      <v-btn
        v-if="editMode && isOrderDirty"
        class="pa-2 add-button"
        outlined
        @click="onSaveEditsClicked()"
      >
        <v-icon>mdi-content-save</v-icon>
      </v-btn>
      <v-btn
        v-if="editMode"
        class="pa-2 add-button"
        outlined
        @click="onCancelEditingClicked()"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </div>
    
    <h2>{{ $ll($lang.text.faq.title) }}</h2>

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
    <div v-else>
      <v-expansion-panels
        v-model="openedItem"
        focusable
        class="mt-4"
      >
        <draggable
          v-model="faqs"
          group="faqs"
          handle=".handle"
        >
          <FaqItem
            v-for="faq in faqs"
            :key="faq.id"
            :faq="faq"
            :in-edit-mode="editMode"
            :on-edit-clicked="editMode ? onItemEditClicked : undefined"
          />
        </draggable>
      </v-expansion-panels>

      <div
        v-if="editMode && $local.user.isAdmin && !isOrderDirty"
        class="text-right"
      >
        <v-btn
          class="mt-3 pa-2 add-button"
          outlined
          @click="onAddItemClicked()"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
  import {GetFaqs} from "../../../domain/faq/usecase/GetFaqs";
  import Vue from 'vue';
  import draggable from 'vuedraggable';
  import {Faq} from "../../../domain/faq/model/Faq";
  import FaqItem from "./components/FaqItem.vue";
  import FaqEditDialog, {EditableFaqs} from "./dialogs/FaqEditDialog.vue";
  import {AddFaq} from "../../../domain/faq/usecase/AddFaq";
  import {EditFaq} from "../../../domain/faq/usecase/EditFaq";
  import {SortFaqs} from "../../../domain/faq/usecase/SortFaqs";
  import {DeleteFaq} from "../../../domain/faq/usecase/DeleteFaq";

  export default Vue.extend({
    components: {FaqEditDialog, FaqItem, draggable},
    services: {
      getFaqs: GetFaqs,
      addFaq: AddFaq,
      editFaq: EditFaq,
      sortFaqs: SortFaqs,
      deleteFaq: DeleteFaq,
    },
    data: function () {
      return {
        loading: false,
        editMode: false,
        openedItem: null as Number | null,
        sourceFaqs: null as Faq[] | null,
        faqs: null as Faq[] | null,
      }
    },
    computed: {
      isOrderDirty: function () {
        if (!this.faqs || !this.sourceFaqs) {
          return false;
        }

        if (this.faqs.length !== this.sourceFaqs.length) {
          return true;
        }

        for (let i = 0; i < this.faqs.length; i++) {
          if (this.faqs[i].id !== this.sourceFaqs[i].id) {
            return true;
          }
        }

        return false;
      },
    },
    created() {
      this.retrieveFaqs()
    },
    methods: {
      retrieveFaqs: function () {
        this.loading = true;

        (this.$services.getFaqs as GetFaqs).execute()
          .then(faqs => {
            this.sourceFaqs = [...faqs];
            this.faqs = [...faqs];
            this.loading = false;
          })
          .catch(error => {
            console.error(error);
            this.loading = false;
          })
      },
      onEditClicked: function () {
        this.editMode = this.$local.user.isAdmin;
      },
      onCancelEditingClicked: function () {
        if (!this.$local.user.isAdmin) {
          return;
        }

        this.faqs = [...this.sourceFaqs ?? []];
        this.editMode = false;
      },
      onSaveEditsClicked: function () {
        if (!this.$local.user.isAdmin) {
          return;
        }

        this.editMode = false;

        if (this.isOrderDirty) {
          const ids = this.faqs?.map(x => x.id);
          if (!ids) return;

          (this.$services.sortFaqs as SortFaqs).execute(ids)
            .then(() => {
              this.retrieveFaqs();
            })
            .catch(error => {
              console.error(error);
            });
        }
      },
      onAddItemClicked: function () {
        this.$refs.faqEditDialog.open();

      },
      onItemEditClicked: function (faqItem: Faq) {
        this.$refs.faqEditDialog.open(faqItem);
      },
      onEditDialogSaveClicked: function (faqItem: EditableFaqs) {
        if (faqItem.id) {
          (this.$services.editFaq as EditFaq).execute(faqItem as Faq)
            .then(() => {
              this.retrieveFaqs();
            })
            .catch(error => {
              console.error(error);
            });
        } else {
          (this.$services.addFaq as AddFaq).execute(faqItem)
            .then(() => {
              this.retrieveFaqs();
            })
            .catch(error => {
              console.error(error);
            });
        }
      },
      onEditDialogDeleteClicked: function (faqItem: Faq) {
        (this.$services.deleteFaq as DeleteFaq).execute(faqItem)
          .then(() => {
            this.retrieveFaqs();
          })
          .catch(error => {
            console.error(error);
          });
      },
    }
  });
</script>
