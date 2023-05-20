<!-- eslint-disable vue/no-mutating-props */ -->
<template>
  <div>
    <v-select
      v-model="signup.foodOption"
      :label="$ll($lang.text.signup.field_food_preference)"
      :items="foodOptions"
      item-text="text"
      item-value="value"
      :disabled="!enabled"
      :rules="validations.foodOptions"
      hide-details="auto"
    />

    <v-text-field
      v-model="signup.allergies"
      :label="$ll($lang.text.signup.field_allergies)"
      :disabled="!enabled"
      :rules="validations.allergies"
      :counter="MAX_STRING_LENGTH"
      hide-details="auto"
      class="mt-8 mb-4"
    />

    <v-text-field
      v-model="signup.extraInfo"
      :label="$ll($lang.text.signup.field_extraInfo)"
      :disabled="!enabled"
      :rules="validations.extraInfo"
      :counter="MAX_STRING_LENGTH"
      hide-details="auto"
      class="my-4"
    />

    <v-checkbox
      v-if="!signup.isIntro"
      v-model="signup.dishwasher"
      :label="$ll($lang.text.signup.field_dishwasher) + (signup.dishwasher ? ' ❤' : '')"
      :disabled="!enabled"
      hide-details="auto"
      class="mt-6"
    />

    <v-checkbox
      v-if="$local.user.isAdmin && !signup.isIntro"
      v-model="signup.cook"
      :label="$ll($lang.text.signup.field_cook)"
      :disabled="!enabled"
      hide-details="auto"
      class="mt-4"
    />
  </div>
</template>

<script lang="ts">
  import Vue, {PropType} from 'vue';
  import {MensaSignup} from "../../../../../domain/signup/model/MensaSignup";
  import {MAX_STRING_LENGTH, Validations} from "../../../../utils/ValidationRules";
  import {FoodOption, SortedFoodOptions} from "../../../../../domain/mensa/model/FoodOption";
  import {Mensa} from "../../../../../domain/mensa/model/Mensa";

  interface FoodOptionSelection {
    value: FoodOption;
    text: string;
  }

  export default Vue.extend({
    props: {
      mensa: {
        type: Object as PropType<Mensa>,
        required: true,
      },
      signup: {
        type: Object as PropType<MensaSignup>,
        required: true,
      },
      enabled: {
        type: Boolean,
        required: false,
      },
    },
    data: () => ({
      foodChosen: null as string | null,
      MAX_STRING_LENGTH: MAX_STRING_LENGTH,
      validations: {
        foodOptions: [Validations.Required, ...Validations.foodOptions],
        allergies: [Validations.MaxStringLengthValidation],
        extraInfo: [Validations.MaxStringLengthValidation],
      }
    }),
    computed: {
      foodOptions: function (): FoodOptionSelection[] {
        let available = SortedFoodOptions.filter(value => this.mensa.foodOptions.includes(value));
        let options = this.allFoodOptions;

        return available.map(function (option: FoodOption): FoodOptionSelection {
          return {
            value: option,
            text: options[option]
          }
        });
      },
      allFoodOptions: function (): { [Property in FoodOption]: string } {
        return {
          vegan: this.$ll(this.$lang.text.foodOptions.vegan),
          vegetarian: this.$ll(this.$lang.text.foodOptions.vegetarian),
          meat: this.$ll(this.$lang.text.foodOptions.meat),
        }
      }
    }
  });
</script>
