<template>
  <v-dialog
    v-model="isOpen"
    max-width="800"
    transition="dialog-bottom-transition"
    :persistent="loading"
  >
    <v-card>
      <v-toolbar>
        <v-card-title>{{ $ll($lang.text.mensa.edit.title) }} {{ formatDate(editor.date) }}</v-card-title>
        <template #extension>
          <v-tabs
            v-model="tab"
            show-arrows
          >
            <v-tabs-slider />
            <v-tab :key="0">
              {{ $ll($lang.text.mensa.edit.tabs.general) }}
            </v-tab>
            <v-tab :key="1">
              {{ $ll($lang.text.mensa.edit.tabs.menu) }}
            </v-tab>
            <v-tab :key="2">
              {{ $ll($lang.text.mensa.edit.tabs.prices) }}
            </v-tab>
            <v-tab :key="3">
              {{ $ll($lang.text.mensa.edit.tabs.signups) }}
            </v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
      <div class="px-3 pt-3">
        <v-form ref="mensaEditForm">
          <v-tabs-items v-model="tab">
            <v-tab-item
              :key="0"
              class="pa-5"
            >
              <v-text-field
                v-model="editor.title"
                :label="$ll($lang.text.mensa.edit.field_title)"
                :disabled="loading"
                :rules="validations.title"
                :counter="MAX_STRING_LENGTH"
                hide-details="auto"
                class="mt-8 mb-4"
              />

              <v-text-field
                v-model="editor.description"
                :label="$ll($lang.text.mensa.edit.field_description)"
                :disabled="loading"
                :rules="validations.description"
                :counter="MAX_STRING_LENGTH"
                hide-details="auto"
                class="mt-8 mb-4"
              />

              <v-menu
                v-model="dateModal"
                :close-on-content-click="false"
                transition="scale-transition"
                :disabled="loading"
                offset-y
                min-width="auto"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    ref="date"
                    v-model="formattedDate"
                    :label="$ll($lang.text.mensa.edit.field_date)"
                    prepend-icon="mdi-calendar"
                    :disabled="loading"
                    readonly
                    v-bind="attrs"
                    class="mt-8 mb-4"
                    v-on="on"
                  />
                </template>
                <DateTimePicker
                  :on-date-time-picked="onDatePicked"
                  :on-close="() => dateModal = false"
                  :original-date="editor.date"
                />
              </v-menu>

              <v-menu
                v-model="closingTimeModal"
                :close-on-content-click="false"
                transition="scale-transition"
                :disabled="loading"
                offset-y
                min-width="auto"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    ref="closingTime"
                    v-model="formattedClosingTime"
                    :label="$ll($lang.text.mensa.edit.field_closing_time)"
                    prepend-icon="mdi-calendar"
                    :disabled="loading"
                    :rules="validations.closingTime"
                    readonly
                    v-bind="attrs"
                    class="mt-8 mb-4"
                    v-on="on"
                  />
                </template>
                <DateTimePicker
                  :on-date-time-picked="onClosingTimePicked"
                  :on-close="() => closingTimeModal = false"
                  :original-date="editor.closingTime"
                />
              </v-menu>
              <v-row class="mb-0">
                <v-col class="pb-0">
                  {{ $ll($lang.text.mensa.edit.field_food_preference) }}
                </v-col>
              </v-row>
              <v-row class="my-0">
                <v-col
                  v-for="(value, key) in allFoodOptions"
                  :key="key"
                  class="py-0"
                  cols="12"
                  md="4"
                  sm="12"
                >
                  <v-checkbox
                    v-model="editor.foodOptions"
                    :disabled="loading"
                    :label="value"
                    :value="key"
                  />
                </v-col>
              </v-row>
              <v-row class="mt-0">
                <v-col
                  v-if="foodOptionError"
                  class="pt-0 error--text"
                >
                  {{ $ll($lang.text.mensa.edit.error_food_preference) }}
                </v-col>
              </v-row>
            </v-tab-item>
            <v-tab-item
              :key="1"
              class="pa-5"
            >
              <v-list>
                <draggable
                  v-model="editor.menu"
                  group="menu"
                  handle=".handle"
                >
                  <div
                    v-for="(item, index) in editor.menu"
                    :key="item.draggableId"
                  >
                    <v-list-item>
                      <v-list-item-icon class="handle">
                        <v-icon>mdi-menu</v-icon>
                      </v-list-item-icon>
                      <v-text-field
                        v-model="item.text"
                        :label="$ll($lang.text.mensa.edit.field_menu_label)"
                        :rules="validations.menuItem"
                      />
                      <v-list-item-icon
                        class="clickable delete-button"
                        @click="editor.menu.splice(index, 1)"
                      >
                        <v-icon>mdi-delete</v-icon>
                      </v-list-item-icon>
                    </v-list-item>
                    <v-divider v-if="index < editor.menu.length - 1" />
                  </div>
                </draggable>
              </v-list>
              <v-btn
                class="pa-2 icon-button"
                outlined
                @click="onAddMenuItemClicked()"
              >
                <v-icon>mdi-plus-thick</v-icon>
              </v-btn>
            </v-tab-item>
            <v-tab-item
              :key="2"
              class="pa-5"
            >
              <v-text-field
                v-model="editor.price"
                :label="$ll($lang.text.mensa.edit.field_base_price)"
                type="number"
                step="0.01"
              />

              <v-divider v-if="editor.extraOptions.length > 0" />
              <v-list>
                <draggable
                  v-model="editor.extraOptions"
                  group="extraOptions"
                  handle=".handle"
                >
                  <div
                    v-for="(item, index) in editor.extraOptions"
                    :key="item.draggableId"
                  >
                    <v-list-item>
                      <v-list-item-icon class="handle">
                        <v-icon>mdi-menu</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-text-field
                          v-model="item.description"
                          :label="$ll($lang.text.mensa.edit.field_extra_option_description_label)"
                        />
                        <v-text-field
                          v-model="item.price"
                          :label="$ll($lang.text.mensa.edit.field_extra_option_price_label)"
                        />
                      </v-list-item-content>
                      <v-list-item-icon
                        class="clickable delete-button"
                        @click="editor.extraOptions.splice(index, 1)"
                      >
                        <v-icon>mdi-delete</v-icon>
                      </v-list-item-icon>
                    </v-list-item>
                    <v-divider />
                  </div>
                </draggable>
              </v-list>
              <v-btn
                class="pa-2 icon-button"
                outlined
                @click="onAddExtraOptionClicked()"
              >
                <v-icon>mdi-plus-thick</v-icon>
              </v-btn>
            </v-tab-item>
            <v-tab-item
              :key="3"
              class="px-5"
            >
              <v-list two-line>
                <v-list-item
                  v-for="item in signups"
                  :key="item.id"
                >
                  <v-list-item-content>
                    <v-list-item-title>
                      {{ item.name }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="text--primary">
                      {{ item.signup.foodOption }}
                    </v-list-item-subtitle>
                  </v-list-item-content>
                  <v-list-item-action>
                    <v-icon class="text--secondary">
                      mdi-square-edit-outline
                    </v-icon>
                  </v-list-item-action>
                </v-list-item>
                <v-divider />
              </v-list>
            </v-tab-item>
          </v-tabs-items>
        </v-form>
      </div>
      <v-card-actions>
        <v-btn
          text
          :loading="loading"
          @click="save()"
        >
          {{ $ll($lang.text.general.save) }}
        </v-btn>
        <v-spacer />
        <v-btn
          text
          :loading="loading"
          @click="isOpen = false"
        >
          {{ $ll($lang.text.general.close) }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import Vue from 'vue';
  import {formatDate} from "../../../../formatters/DateFormatter";
  import {MAX_STRING_LENGTH, Validations} from "../../../../utils/ValidationRules";
  import DateTimePicker from "../../../../components/common/DateTimePicker.vue";
  import {UpdateMensa} from "../../../../../domain/mensa/usecase/UpdateMensa";
  import draggable from 'vuedraggable';
  import {EditMensa} from "../../../../../domain/mensa/model/EditMensa";
  import {v4 as uuidv4} from "uuid";
  import {FoodOption} from "../../../../../domain/mensa/model/FoodOption";
  import {GetMensa} from "../../../../../domain/mensa/usecase/GetMensa";
  import {SignupUser} from "../../../../../domain/mensa/model/SignupUser";

  interface TempMenuItem {
    draggableId: string;
    id?: string;
    text: string;
  }

  interface TempExtraOption {
    draggableId: string;
    id?: string;
    description: string;
    price: number;
  }

  export default Vue.extend({
    components: {DateTimePicker, draggable},
    services: {
      updateMensa: UpdateMensa,
      getMensa: GetMensa
    },
    data: function () {
      return {
        isOpen: false,
        loading: false,
        tab: 0,
        dateModal: false,
        closingTimeModal: false,
        MAX_STRING_LENGTH: MAX_STRING_LENGTH,
        validations: {
          title: [Validations.Required, Validations.MaxStringLengthValidation],
          description: [Validations.MaxStringLengthValidation],
          foodOptions: [Validations.Required, ...Validations.foodOptions],
          date: [Validations.Required],
          closingTime: [Validations.Required],
          maxSignups: [Validations.Required, ...Validations.integer],
          price: [Validations.Required, ...Validations.price],
          menuItem: [Validations.MaxStringLengthValidation]
        },
        editor: {
          id: undefined as string | undefined,
          title: "" as string,
          description: "" as string,
          foodOptions: [] as FoodOption[],
          menu: [] as TempMenuItem[],
          extraOptions: [] as TempExtraOption[],
          date: new Date(),
          closingTime: new Date(),
          price: 0.0,
          maxSignups: 0
        },
        foodOptionError: false,
        signups: undefined as SignupUser[] | undefined
      }
    },
    computed: {
      formattedDate: function (): string {
        return formatDate(this.editor.date);
      },
      formattedClosingTime: function (): string {
        return formatDate(this.editor.closingTime);
      },
      allFoodOptions: function (): { [Property in FoodOption]: string } {
        return {
          vegan: this.$ll(this.$lang.text.foodOptions.vegan),
          vegetarian: this.$ll(this.$lang.text.foodOptions.vegetarian),
          meat: this.$ll(this.$lang.text.foodOptions.meat),
        }
      }
    },
    watch: {
      'editor.foodOptions': function (newOptions) {
        this.foodOptionError = newOptions.length <= 0;
      }
    },
    methods: {
      open: function (mensa: EditMensa) {
        if (!this.$local.user.isAdmin) return;

        let tempMenu = mensa.menu.map(item => {
          return {
            draggableId: uuidv4(),
            id: item.id,
            text: item.text
          }
        });
        let tempExtraOptions = mensa.extraOptions.map(item => {
          return {
            draggableId: uuidv4(),
            id: item.id,
            description: item.description,
            price: item.price
          }
        });

        this.editor = {
          id: mensa.id,
          title: mensa.title,
          description: mensa.description,
          foodOptions: mensa.foodOptions,
          menu: tempMenu,
          extraOptions: tempExtraOptions,
          date: mensa.date,
          closingTime: mensa.closingTime,
          price: mensa.price,
          maxSignups: mensa.maxSignups
        }

        this.isOpen = true;
      },
      save: function () {
        if (!this.$refs.mensaEditForm.validate()) {
          return;
        }

        if (this.editor.foodOptions.length <= 0) {
          // TODO food options check
          return
        }

        this.loading = true;

        let menu = this.editor.menu
          .map(item => ({id: item.id, text: item.text}));
        let extraOptions = this.editor.extraOptions
          .map(item => ({id: item.id, description: item.description, price: item.price}));

        let mensa: EditMensa = {
          id: this.editor.id,
          title: this.editor.title,
          description: this.editor.description,
          foodOptions: this.editor.foodOptions,
          menu: menu,
          extraOptions: extraOptions,
          date: this.editor.date,
          closingTime: this.editor.closingTime,
          price: this.editor.price,
          maxSignups: this.editor.maxSignups
        };

        (this.$services.updateMensa as UpdateMensa).execute(mensa)
          .then(() => {
            this.loading = false;
            this.isOpen = false
          })
          .catch(error => {
            this.loading = false
            console.error(error)
          })
      },
      onDatePicked: function (date: Date) {
        this.editor.date = date;
      },
      onClosingTimePicked: function (date: Date) {
        this.editor.closingTime = date;
      },
      onAddMenuItemClicked: function () {
        this.editor.menu.push({
          draggableId: uuidv4(),
          text: ""
        });
      },
      onAddExtraOptionClicked: function () {
        this.editor.extraOptions.push({
          draggableId: uuidv4(),
          description: "",
          price: 0.0
        });
      },
      formatDate: formatDate
    }
  });
</script>

<style lang="css">
  .handle {
    cursor: grab;
    align-self: center;
  }

  .delete-button {
    align-self: center;
  }

  .clickable {
    cursor: pointer;
  }

  .icon-button {
    min-width: 0 !important;
  }
</style>
