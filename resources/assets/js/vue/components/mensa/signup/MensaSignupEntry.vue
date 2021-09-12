<template>
    <div>
        <v-select
            :label="$ll($lang.text.signup.field_food_preference)"
            :items="foodOptions"
            v-model="foodChosen"
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
            :label="$ll($lang.text.signup.field_dishwasher)"
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
import {Validations, MAX_STRING_LENGTH} from "../../../../utils/ValidationRules";

export default Vue.extend({
    props: {
        signup: {
            type: Object as PropType<MensaSignup>,
            required: true
        },
    },
    data: () => ({
        foodOptions: [] as string[],
        foodChosen: null as string | null,
        MAX_STRING_LENGTH: MAX_STRING_LENGTH,
        validations: {
            foodOptions: Validations.foodOptions,
            allergies: Validations.allergies,
            description: Validations.description,
        }
    }),
    mounted() {
        this.foodOptions = [this.optionVegetarian, this.optionMeat];
    },
    watch: {
        vegetarianChosen: function(option: string) {
            this.signup.vegetarian = option == this.optionVegetarian;
        },
        signup: function(after: MensaSignup, before: MensaSignup) {
            if (before !== after) {
                this.foodChosen = null;
            }
        }
    },
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
