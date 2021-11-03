<template>
    <div class="container mt-3 mb-3">
        <MensaSignupDialog ref="signupDialog" />
        <MensaOverviewDialog ref="overviewDialog" />
        <v-banner>
            <div class="d-flex">
                <div>
                    <span class="text--secondary">{{ $ll($lang.text.mensa.mensas_between) }}</span> {{ startDate }}
                    <span class="text--secondary">{{ $ll($lang.text.and) }}</span> {{ endDate }}
                </div>
                <v-spacer></v-spacer>
                <div>
                    <v-btn @click="offsetWeeks(-2)" :loading="loading" class="mr-4">{{ $ll($lang.text.mensa.previous_weeks) }}</v-btn>
                    <v-btn @click="offsetWeeks(2)" :loading="loading">{{ $ll($lang.text.mensa.next_weeks) }}</v-btn>
                </div>
            </div>
        </v-banner>

        <v-expansion-panels focusable accordion v-model="openedItem" class="mt-4">
            <MensaItem v-for="mensa in this.mensas" :key="mensa.id" :mensa="mensa" :on-signup-clicked="onSignupMensaClicked" :on-overview-clicked="onMensaOverviewClicked" />
        </v-expansion-panels>

        <div class="mt-4 d-flex">
            <v-spacer></v-spacer>
        </div>
    </div>
</template>

<script lang="ts">
    import GetMensas from "../../../domain/mensa/usecase/GetMensas";
    import Mensa from "../../../domain/mensa/model/Mensa";
    import Vue from 'vue';
    import MensaItem from '../../components/mensa/MensaItem.vue';
    import {Between} from "../../../domain/mensa/model/MensaList";
    import {formatDate} from "../../formatters/DateFormatter";
    import MensaSignupDialog from "../../components/mensa/signup/MensaSignupDialog.vue";
    import MensaOverviewDialog from "../../components/mensa/signup/MensaOverviewDialog";
    import {AuthUser, User} from "../../../domain/common/model/User";

    export default Vue.extend({
        components: {MensaOverviewDialog, MensaSignupDialog, MensaItem},
        data: function() {
            return {
                openedItem: null,
                mensas: [] as Mensa[],
                weekOffset: -1,
                loading: true,
                between: null as Between|null,
                mensaSignup: null as Mensa|null,
            }
        },
        computed: {
            startDate: function(): string|null {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.start, { withTime: false });
            },
            endDate: function(): string|null {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.end, { withTime: false });
            },
            currentUser: function(): AuthUser {
                return this.$local.user;
            }
        },
        watch: {
            weekOffset: function (offset) {
                this.retrieveMensas(offset);
            },
            currentUser: function () {
                this.retrieveMensas(this.weekOffset);
            }
        },
        methods: {
            offsetWeeks: function (offset: number) {
                this.weekOffset += offset;
            },
            onSignupMensaClicked: function (mensa: Mensa) {
                (this.$refs.signupDialog as any).open(mensa);
            },
            onMensaOverviewClicked: function (mensa: Mensa) {
                (this.$refs.overviewDialog as any).open(mensa);
            },
            retrieveMensas: function(offset: number) {
                this.loading = true;

                GetMensas(offset)
                    .then(mensaList => {
                        this.openedItem = null;
                        this.mensas = [...mensaList.mensas];
                        this.between = mensaList.between;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error(error);
                        this.loading = false;
                    })
            }
        },
        created() {
            this.weekOffset = 0;
        }
    });
</script>
