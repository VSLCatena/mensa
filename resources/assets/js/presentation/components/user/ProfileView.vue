<template>
  <v-card outlined>
    <v-toolbar>
      <v-card-title>{{ $ll($lang.text.profile.title) }} {{ $local.user.name }}</v-card-title>
    </v-toolbar>
    <v-form ref="loginForm" class="px-5 pt-3">
      <v-select
        :label="$ll($lang.text.signup.field_food_preference)"
        :items="foodOptions"
        item-text="text"
        item-value="value"
        v-model="foodPreference"
        :disabled="!enabled"
        :rules="validations.foodOptions"
        hide-details="auto"></v-select>

      <v-text-field
        :label="$ll($lang.text.signup.field_allergies)"
        v-model="allergies"
        :disabled="!enabled"
        :rules="validations.allergies"
        :counter="MAX_STRING_LENGTH"
        hide-details="auto"
        class="mt-8 mb-4"/>

      <v-text-field
        :label="$ll($lang.text.signup.field_extraInfo)"
        v-model="extraInfo"
        :disabled="!enabled"
        :rules="validations.extraInfo"
        :counter="MAX_STRING_LENGTH"
        hide-details="auto"
        class="my-4"/>
    </v-form>
    <v-card-actions>
      <v-dialog v-model="logoutConfirmation" max-width="290">
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on">{{ $ll($lang.text.login.logout_button) }}</v-btn>
        </template>
        <v-card>
          <v-card-title class="text-h5">{{ $ll($lang.text.logout.title) }}</v-card-title>
          <v-card-text>{{ $ll($lang.text.logout.confirm) }}</v-card-text>
          <v-card-actions>
            <v-btn text @click="logout()">{{ $ll($lang.text.general.yes) }}</v-btn>
            <v-spacer></v-spacer>
            <v-btn text @click="logoutConfirmation = false">{{ $ll($lang.text.general.cancel) }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-spacer></v-spacer>
      <v-btn text @click="save()" :disabled="!hasSettingsChanged" :loading="!enabled">
        {{ $ll($lang.text.general.save) }}
      </v-btn>
      <v-btn text @click="close()">{{ $ll($lang.text.general.close) }}</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
  import Vue from 'vue';
  import {MAX_STRING_LENGTH, Validations} from "../../utils/ValidationRules";
  import {Logout} from "../../../domain/user/usecase/Logout";
  import {AnonymousUser, isLoggedIn, UserPreferences} from "../../../domain/common/model/User";
  import {FoodOption, SortedFoodOptions} from "../../../domain/mensa/model/FoodOption";
  import {UpdateSelf} from "../../../domain/user/usecase/UpdateSelf";

  interface FoodOptionSelection {
    value: FoodOption | null;
    text: string;
  }

  export default Vue.extend({
    services: {
      logout: Logout,
      updateSelf: UpdateSelf
    },
    props: {
      close: {
        type: Function,
        required: true
      },
      isOpen: Boolean,
    },
    data: function () {
      return {
        logoutConfirmation: false,
        enabled: true,
        allergies: "",
        extraInfo: "",
        foodPreference: null as FoodOption | null,
        MAX_STRING_LENGTH: MAX_STRING_LENGTH,
        validations: {
          foodOptions: Validations.foodOptions,
          allergies: [Validations.MaxStringLengthValidation],
          description: [Validations.MaxStringLengthValidation],
        }
      }
    },
    methods: {
      logout: function () {
        (this.$services.logout as Logout).execute();
        this.logoutConfirmation = false;
        this.$local.user = AnonymousUser;
      },
      save: function () {
        this.enabled = false;
        let user = this.$local.user;

        if (!isLoggedIn(user)) {
          return;
        }

        let newUser: UserPreferences = {
          allergies: this.allergies,
          extraInfo: this.extraInfo,
          foodPreference: this.foodPreference
        };

        (this.$services.updateSelf as UpdateSelf).execute(newUser)
          .then(() => {
            this.$local.user = {...this.$local.user, ...newUser};
            this.enabled = true;
            this.close();
          }).catch(() => {
          this.enabled = true;
        });
      },
      resetData: function () {
        let user = this.$local.user;

        if(!isLoggedIn(user)) {
          return;
        }

        this.allergies = user.allergies ?? "";
        this.extraInfo = user.extraInfo ?? "";
        this.foodPreference = user.foodPreference ?? null;
      }
    },
    mounted: function () {
      this.resetData();
    },
    watch: {
      isOpen: function (isOpen: boolean) {
        if (!isOpen) return;
        this.resetData();
      }
    },
    computed: {
      foodOptions: function (): FoodOptionSelection[] {
        let options = this.allFoodOptions;

        let sorted = SortedFoodOptions.map(function (option: FoodOption): FoodOptionSelection {
          return {
            value: option,
            text: options[option]
          }
        });

        return [
          {value: null, text: options.none},
          ...sorted
        ]
      },
      allFoodOptions: function (): { [Property in FoodOption]: string } & { none: string } {
        return {
          none: this.$ll(this.$lang.text.foodOptions.none),
          vegan: this.$ll(this.$lang.text.foodOptions.vegan),
          vegetarian: this.$ll(this.$lang.text.foodOptions.vegetarian),
          meat: this.$ll(this.$lang.text.foodOptions.meat),
        }
      },
      hasSettingsChanged: function (): boolean {
        let user = this.$local.user;
        if (!isLoggedIn(user)) {
          return false;
        }
        return user.allergies != this.allergies || user.extraInfo != this.extraInfo ||
          user.foodPreference != this.foodPreference;
      }
    }
  });
</script>
