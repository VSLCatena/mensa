<template>
    <div class="container mt-3 mb-3">
        <v-expansion-panels focusable>
            <MensaItem v-for="mensa in this.mensas" :key="mensa.id" :mensa="mensa" />
        </v-expansion-panels>
    </div>
</template>

<script lang="ts">
    import GetMensas from "../../../domain/mensa/usecase/GetMensas";
    import Mensa from "../../../domain/mensa/model/Mensa";
    import Vue from 'vue';
    import MensaItem from './components/MensaItem.vue';

    export default Vue.extend({
        components: {MensaItem},
        mounted() {
            GetMensas(20)
                .then(mensas => this.mensas = [...mensas])
                .catch(error => console.error(error))
        },
        data() {
            return {
                mensas: [] as Mensa[]
            }
        }
    });
</script>
