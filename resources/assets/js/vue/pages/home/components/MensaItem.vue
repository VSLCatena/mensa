<template>
    <v-expansion-panel>
        <v-expansion-panel-header v-slot="{ open }">
            <div>
                <h4 class="mb-1">{{ mensa.title }}</h4>
                <small>{{ formattedDate }}</small>
            </div>
            <v-spacer></v-spacer>
            <v-fade-transition leave-absolute>
                <span class="flex-grow-0 mr-5" v-if="!open">{{ mensa.signups }} / {{ mensa.maxSignups }}</span>
            </v-fade-transition>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <div class="d-flex flex-column flex-md-row pt-3">
                <div class="ml-0 ml-md-6 order-md-12">
                    <v-card outlined class="pa-4">
                        <table class="text-no-wrap">
                            <tr>
                                <td class="pr-2">{{ $ll($lang.text.mensa.signups, true) }}:</td>
                                <td>{{ mensa.signups }} / {{ mensa.maxSignups }}</td>
                            </tr>
                            <tr v-if="mensa.dishwashers > 0">
                                <td class="pr-2">{{ $ll($lang.text.mensa.dishwashers) }}:</td>
                                <td>{{ mensa.dishwashers }}</td>
                            </tr>
                            <tr>
                                <td class="pr-2">{{ $ll($lang.text.mensa.closingtime, true) }}:</td>
                                <td>{{ closingTime }}</td>
                            </tr>
                            <tr v-if="mensa.price != 0">
                                <td class="pr-2">{{ $ll($lang.text.mensa.price, true) }}:</td>
                                <td>&euro; {{ mensa.price.toFixed(2) }}</td>
                            </tr>
                        </table>
                    </v-card>
                </div>
                <div>
                    <div v-if="mensa.cooks.length > 0" class="mt-4 mt-md-0 mb-4">
                        {{ $ll($lang.text.mensa.cooked_by) }}: <span class="text--secondary">{{ cooks }}</span>
                    </div>
                    <div v-if="mensa.description.length > 0" class="text--secondary flex-grow-1 mt-4 mt-md-0">{{ mensa.description }}</div>
                    <div class="mt-4">
                        <v-btn color="primary">{{ $ll($lang.text.mensa.button_signup) }}</v-btn>
                        <v-btn outlined>{{ $ll($lang.text.mensa.button_overview) }}</v-btn>
                    </div>
                </div>
            </div>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script lang="ts">
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import { formatDate } from "../../../formatters/DateFormatter";
    import { PropType } from "vue";
    import {capitalize, formatUsers} from "../../../formatters/StringFormatter";
    import lang from '../../../../lang/Language';

    export default {
        props: {
            mensa: {
                type: Object as PropType<Mensa>,
                required: true
            }
        },
        computed: {
            formattedDate: function() {
                return capitalize(formatDate(this.mensa.date));
            },
            closingTime: function() {
                return formatDate(this.mensa.closingTime, { withDate: false });
            },
            cooks: function() {
                return formatUsers(this.mensa.cooks);
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
