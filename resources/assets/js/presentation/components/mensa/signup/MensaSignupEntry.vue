<template>
    <div>
        <v-select
            :label="$ll($lang.text.signup.field_food_preference)"
            :items="foodOptions"
            item-text="text"
            item-value="value"
            v-model="signup.foodPreference"
            :disabled="!enabled"
            :rules="validations.foodOptions"
            hide-details="auto"></v-select>

        <v-text-field
            :label="$ll($lang.text.signup.field_allergies)"
            v-model="signup.allergies"
            :disabled="!enabled"
            :rules="validations.allergies"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="mt-8 mb-4" />

        <v-text-field
            :label="$ll($lang.text.signup.field_extraInfo)"
            v-model="signup.extraInfo"
            :disabled="!enabled"
            :rules="validations.extraInfo"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="my-4" />

        <v-checkbox
            :label="$ll($lang.text.signup.field_dishwasher) + (signup.dishwasher ? ' ❤' : '')"
            v-if="!signup.isIntro"
            v-model="signup.dishwasher"
            :disabled="!enabled"
            hide-details="auto"
            class="mt-6" />

        <v-checkbox
            v-if="$local.user.isAdmin && !signup.isIntro"
            :label="$ll($lang.text.signup.field_cook)"
            v-model="signup.cook"
            :disabled="!enabled"
            hide-details="auto"
            class="mt-4" />
    </div>
</template>

<script lang="ts">
import Vue, {PropType} from 'vue';
import MensaSignup from "../../../../domain/mensa/model/MensaSignup";
import {MAX_STRING_LENGTH, Validations} from "../../../utils/ValidationRules";
import FoodPreference, {SortedFoodPreferences} from "../../../../domain/mensa/model/FoodPreference";
import Mensa from "../../../../domain/mensa/model/Mensa";

interface FoodOption {
    value: FoodPreference,
    text: string
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
        foodOptions: function(): FoodOption[] {
            let available = SortedFoodPreferences.filter(value => this.mensa.foodOptions.includes(value));
            let options = this.allFoodOptions;

            return available.map(function(option: FoodPreference): FoodOption {
                return {
                    value: option,
                    text: options[option]
                }
            });
        },
        allFoodOptions: function(): { [Property in FoodPreference]: string } {
            return {
                VEGAN: this.$ll(this.$lang.text.foodOptions.vegan),
                VEGETARIAN: this.$ll(this.$lang.text.foodOptions.vegetarian),
                MEAT: this.$ll(this.$lang.text.foodOptions.meat),
            }
        }
    }
});
</script>
