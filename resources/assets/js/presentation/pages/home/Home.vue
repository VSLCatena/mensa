<template>
    <div class="container mt-3 mb-3">
        <MensaSignupDialog ref="mensaSignupDialog"/>
        <MensaOverviewDialog ref="mensaOverviewDialog"/>
        <MensaEditDialog ref="mensaEditDialog"/>
        <v-banner>
            <div class="d-flex">
                <div>
                    <span class="text--secondary">{{ $ll($lang.text.mensa.mensas_between) }}</span> {{ startDate }}
                    <span class="text--secondary">{{ $ll($lang.text.and) }}</span> {{ endDate }}
                </div>
                <v-spacer></v-spacer>
                <div>
                    <v-btn @click="offsetWeeks(-2)" :loading="loading" class="mr-4">
                        {{ $ll($lang.text.mensa.previous_weeks) }}
                    </v-btn>
                    <v-btn @click="offsetWeeks(2)" :loading="loading">{{ $ll($lang.text.mensa.next_weeks) }}</v-btn>
                </div>
            </div>
        </v-banner>

        <div class="mt-4" v-if="loading">
            <v-skeleton-loader v-for="x in 5" :key="x" type="list-item-two-line" class="mt-1" />
        </div>

        <v-expansion-panels v-else focusable accordion v-model="openedItem" class="mt-4">
            <MensaItem
                v-for="mensa in this.mensas" :key="mensa.id"
                :mensa="mensa"
                :on-signup-clicked="onSignupMensaClicked"
                :on-overview-clicked="onMensaOverviewClicked"
                :on-edit-clicked="onMensaEditClicked"/>
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
    import MensaSignupDialog from "../../components/mensa/dialogs/signup/MensaSignupDialog.vue";
    import MensaOverviewDialog from "../../components/mensa/dialogs/overview/MensaOverviewDialog.vue";
    import {AuthUser} from "../../../domain/common/model/User";
    import MensaEditDialog from "../../components/mensa/dialogs/mensa/MensaEditDialog.vue";

    export default Vue.extend({
        components: {MensaEditDialog, MensaOverviewDialog, MensaSignupDialog, MensaItem},
        data: function () {
            return {
                openedItem: null,
                mensas: [] as Mensa[],
                weekOffset: -1,
                loading: true,
                between: null as Between | null,
                mensaSignup: null as Mensa | null,
            }
        },
        computed: {
            startDate: function (): string | null {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.start, {withTime: false});
            },
            endDate: function (): string | null {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.end, {withTime: false});
            },
            currentUser: function (): AuthUser {
                return this.$local.user;
            }
        },
        watch: {
            weekOffset: function (offset) {
                this.retrieveMensas(offset, true);
            },
            currentUser: function () {
                this.retrieveMensas(this.weekOffset, false);
            }
        },
        methods: {
            offsetWeeks: function (offset: number) {
                this.weekOffset += offset;
            },
            onSignupMensaClicked: function (mensa: Mensa) {
                (this.$refs.mensaSignupDialog as any).open(mensa);
            },
            onMensaOverviewClicked: function (mensa: Mensa) {
                (this.$refs.mensaOverviewDialog as any).open(mensa);
            },
            onMensaEditClicked: function (mensa: Mensa) {
                (this.$refs.mensaEditDialog as any).open(mensa, mensa.signups);
            },
            retrieveMensas: function (offset: number, clearItem: boolean) {
                this.loading = true;

                GetMensas(offset)
                    .then(mensaList => {
                        if (clearItem) this.openedItem = null;
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
