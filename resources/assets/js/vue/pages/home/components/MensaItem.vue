<template>
    <v-expansion-panel>
        <v-expansion-panel-header v-slot="{ open }">
            <div>
                <h4 class="mb-1">{{ mensa.title }}</h4>
                <small>{{ formattedDate }}</small>
            </div>
            {{ mensa.signups }} / {{ mensa.maxSignups }}
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            {{ mensa["description"] }}

            <span v-if="mensa['signups'] === 1">Er is 1 inschrijving</span>
            <span v-else>Er zijn {{ mensa['signups'] }} inschrijvingen</span>

            <div class="btn btn-success" v-if="mensa['signups'] > 0">Inschrijven</div>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script lang="ts">
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import { formatDate } from "../../../utils/DateFormatter";
    import { PropType } from "vue";

    export default {
        props: {
            mensa: {
                type: Object as PropType<Mensa>,
                required: true
            }
        },
        computed: {
            formattedDate: function() {
                return formatDate(this.mensa.date);
            },
            closingTime: function() {
                return formatDate(this.mensa.closingTime);
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
