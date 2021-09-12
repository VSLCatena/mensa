<template>
    <div>
        <v-select
            :label="$ll($lang.text.signup.field_food_preference)"
            :items="foodOptions"
            item-text="text"
            item-value="value"
            v-model="signup.foodPreference"
            :rules="validations.foodOptions"
            hide-details="auto"></v-select>

        <v-text-field
            :label="$ll($lang.text.signup.field_allergies)"
            v-model="signup.allergies"
            :rules="validations.allergies"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="mt-8 mb-4" />

        <v-text-field
            :label="$ll($lang.text.signup.field_description)"
            v-model="signup.description"
            :rules="validations.description"
            :counter="MAX_STRING_LENGTH"
            hide-details="auto"
            class="my-4" />

        <v-checkbox
            :label="$ll($lang.text.signup.field_dishwasher) + (signup.dishwasher ? ' ❤' : '')"
            v-model="signup.dishwasher"
            hide-details="auto"
            class="mt-6" />

        <v-checkbox
            v-if="$user.isAdmin"
            :label="$ll($lang.text.signup.field_cook)"
            v-model="signup.cook"
            hide-details="auto"
            class="mt-4" />
    </div>
</template>

<script lang="ts">
import Vue, {PropType} from 'vue';
import MensaSignup from "../../../../domain/mensa/model/MensaSignup";
import {MAX_STRING_LENGTH, Validations} from "../../../../utils/ValidationRules";
import FoodPreference from "../../../../domain/mensa/model/FoodPreference";
import Language, {CurrentLanguage} from "../../../../lang/Language";

const foodOptions = [
    {
        value: FoodPreference.VEGETARIAN,
        text: CurrentLanguage.language.getText(Language.text.signup.field_food_vegetarian),
    },
    {
        value: FoodPreference.MEAT,
        text: CurrentLanguage.language.getText(Language.text.signup.field_food_meat),
    }
];

export default Vue.extend({
    props: {
        signup: {
            type: Object as PropType<MensaSignup>,
            required: true
        },
    },
    data: () => ({
        foodOptions: foodOptions,
        foodChosen: null as string | null,
        MAX_STRING_LENGTH: MAX_STRING_LENGTH,
        validations: {
            foodOptions: Validations.foodOptions,
            allergies: Validations.allergies,
            description: Validations.description,
        }
    }),
    computed: {
        optionVegetarian: function(): string {
            return this.$ll(this.$lang.text.signup.field_food_vegetarian);
        },
        optionMeat: function(): string {
            return this.$ll(this.$lang.text.signup.field_food_meat);
        }
    }
});
</script>
