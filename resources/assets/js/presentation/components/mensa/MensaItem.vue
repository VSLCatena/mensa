<template>
    <v-expansion-panel>
        <v-expansion-panel-header v-slot="{ open }">
            <div>
                <h4 class="mb-1">{{ mensa.title }}</h4>
                <small>{{ formattedDate }}</small>
            </div>
            <v-spacer></v-spacer>
            <v-fade-transition leave-absolute>
                <div class="flex-grow-0 mr-5 text-no-wrap" v-if="!open">
                    {{ signupCount }} / {{ mensa.maxSignups }}<br />
                    {{ dishwasherCount }}
                </div>
            </v-fade-transition>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <div class="d-flex flex-column flex-md-row pt-3">
                <div class="ml-0 ml-md-6 order-md-12">
                    <v-card outlined class="pa-4">
                        <table class="text-no-wrap">
                            <tr>
                                <td class="pr-2">{{ $ll($lang.text.mensa.signups, true) }}:</td>
                                <td class="text--secondary">{{ signupCount }} / {{ mensa.maxSignups }}</td>
                            </tr>
                            <tr v-if="mensa.dishwashers > 0">
                                <td class="pr-2">{{ $ll($lang.text.mensa.dishwashers) }}:</td>
                                <td class="text--secondary">{{ mensa.dishwashers }}</td>
                            </tr>
                            <tr>
                                <td class="pr-2">{{ $ll($lang.text.mensa.closingtime, true) }}:</td>
                                <td class="text--secondary">{{ closingTime }}</td>
                            </tr>
                            <tr v-if="mensa.price != 0">
                                <td class="pr-2">{{ $ll($lang.text.mensa.price, true) }}:</td>
                                <td class="text--secondary">&euro; {{ mensa.price.toFixed(2) }}</td>
                            </tr>
                        </table>
                    </v-card>
                </div>
                <div>
                    <div v-if="mensa.cooks.length > 0" class="mt-4 mt-md-0 mb-4">
                        {{ $ll($lang.text.mensa.cooked_by) }}: <span class="text--secondary">{{ cooks }}</span>
                    </div>
                    <div v-if="mensa.description.length > 0" class="text--secondary flex-grow-1 mt-4 mt-md-0">{{ mensa.description }}</div>

                    <div class="mt-4" v-if="mensa.menu.length > 0 || mensa.extraOptions.length > 0">
                        <v-tabs v-model="infoTabs">
                            <v-tabs-slider></v-tabs-slider>
                            <v-tab key="menu" v-if="mensa.menu.length > 0">{{ $ll($lang.text.mensa.menu_title) }}</v-tab>
                            <v-tab key="extraOptions" v-if="mensa.extraOptions.length > 0">{{ $ll($lang.text.mensa.extra_options_title) }}</v-tab>
                        </v-tabs>

                        <v-tabs-items v-model="infoTabs" class="mt-2">
                            <v-tab-item key="menu" v-if="mensa.menu.length > 0">
                                <table style="border-spacing: 0 8px;">
                                    <tr v-for="item in mensa.menu">
                                        <td style="vertical-align: top" class="px-2">-</td>
                                        <td class="text--secondary">{{ item.text }}</td>
                                    </tr>
                                </table>
                            </v-tab-item>
                            <v-tab-item key="extraOptions" v-if="mensa.extraOptions.length > 0">
                                <table style="border-spacing: 0 8px;">
                                    <tr v-for="item in mensa.extraOptions">
                                        <td style="vertical-align: top" class="px-2">-</td>
                                        <td class="text--secondary">{{ item.description }}</td>
                                    </tr>
                                </table>
                            </v-tab-item>
                        </v-tabs-items>
                    </div>

                    <div class="mt-6">
                        <v-btn color="primary" :disabled="mensa.signups >= mensa.maxSignups" @click="onSignupClicked(mensa)">{{ $ll($lang.text.mensa.button_signup) }}</v-btn>
                        <v-btn outlined v-if="isLoggedIn" @click="onOverviewClicked(mensa)">{{ $ll($lang.text.mensa.button_overview) }}</v-btn>
                    </div>
                </div>
            </div>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script lang="ts">
    import Mensa from "../../../domain/mensa/model/Mensa";
    import { formatDate } from "../../formatters/DateFormatter";
    import Vue, { PropType } from "vue";
    import {capitalize, formatUsers} from "../../formatters/StringFormatter";
    import {LanguageText} from "../../lang/LanguageText";
    import {AnonymousUser} from "../../../domain/common/model/User";

    export default Vue.extend({
        data: function() {
            return {
                infoTabs: null,
            }
        },
        props: {
            mensa: {
                type: Object as PropType<Mensa>,
                required: true
            },
            onSignupClicked: {
                type: Function,
                required: false
            },
            onOverviewClicked: {
                type: Function,
                required: false
            }
        },
        computed: {
            isLoggedIn: function(): boolean {
                return this.$local.user != AnonymousUser;
            },
            formattedDate: function(): string {
                return capitalize(formatDate(this.mensa.date));
            },
            closingTime: function(): string {
                return formatDate(this.mensa.closingTime, { withDate: false });
            },
            cooks: function(): string|null {
                return formatUsers(this.mensa.cooks);
            },
            signupCount: function(): number {
                let users = this.mensa.signups;
                if (Array.isArray(users)) {
                    return users.length;
                }
                return users;
            },
            dishwasherCount: function(): string {
                let ref = this.$lang.text.mensa.dishwasher_count;
                let textRef: LanguageText;
                switch (this.mensa.dishwashers) {
                    case 0:
                        textRef = ref.zero;
                        break;
                    case 1:
                        textRef = ref.one;
                        break;
                    default:
                        textRef = ref.many;
                        break;
                }
                return this.$ll(textRef).replace('%s', this.mensa.dishwashers.toString());
            },
            signups: function(): string {
                let users = this.mensa.signups;
                if (Array.isArray(users)) {
                    return formatUsers(users) ?? "";
                }
                return users.toString();
            }
        },
    });
</script>
