<template>
    <div>

        {{ signup.isIntro }}
        vegetarian: boolean,
        isIntro: boolean,
        description: string,
        cook: boolean,
        dishwasher: boolean,
    </div>
</template>

<script lang="ts">
import Vue, {PropType} from 'vue';
import Mensa from "../../../../domain/mensa/model/Mensa";
import {formatDate} from "../../../formatters/DateFormatter";
import MensaSignup from "../../../../domain/mensa/model/MensaSignup";

export default Vue.extend({
    props: {
        signup: {
            type: Object as PropType<MensaSignup>,
            required: true
        },
    }
});
</script>
