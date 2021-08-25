<template>
    <div class="container mt-3 mb-3">
        <v-banner>
            <span class="text--secondary">{{ $ll($lang.text.mensa.mensas_between) }}</span> {{ startDate }}
            <span class="text--secondary">{{ $ll($lang.text.and) }}</span> {{ endDate }}
        </v-banner>

        <v-expansion-panels focusable accordion class="mt-4">
            <MensaItem v-for="mensa in this.mensas" :key="mensa.id" :mensa="mensa" />
        </v-expansion-panels>

        <div class="mt-4 d-flex">
            <v-btn @click="offsetWeeks(-2)" :loading="loading">{{ $ll($lang.text.mensa.previous_weeks) }}</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="offsetWeeks(2)" :loading="loading">{{ $ll($lang.text.mensa.next_weeks) }}</v-btn>
        </div>
    </div>
</template>

<script lang="ts">
    import GetMensas from "../../../domain/mensa/usecase/GetMensas";
    import Mensa from "../../../domain/mensa/model/Mensa";
    import Vue from 'vue';
    import MensaItem from './components/MensaItem.vue';
    import {Between} from "../../../domain/mensa/model/MensaList";
    import {formatDate} from "../../formatters/DateFormatter";

    export default Vue.extend({
        components: {MensaItem},
        data() {
            return {
                mensas: [] as Mensa[],
                weekOffset: null as number|null,
                loading: true,
                between: null as Between|null,
            }
        },
        computed: {
            startDate: function() {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.start, { withTime: false });
            },
            endDate: function() {
                let between = this.between;
                if (between == null) return null;
                return formatDate(between.end, { withTime: false });
            }
        },
        watch: {
            weekOffset: function (offset) {
                this.loading = true;

                GetMensas(offset)
                    .then(mensaList => {
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
        methods: {
            offsetWeeks: function (offset: number) {
                this.weekOffset += offset;
            }
        },
        created() {
            this.weekOffset = 0;
        }
    });
</script>
