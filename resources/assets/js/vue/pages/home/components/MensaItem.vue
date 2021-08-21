<template>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">{{ mensa["title"] }}</div>

            <div class="panel-body">
                {{ mensa["description"] }}
                <span v-if="mensa['signups'] == 1">Er is 1 inschrijving</span>
                <span v-else>Er zijn {{ mensa['signups'] }} inschrijvingen</span>

                <div class="btn btn-success" v-if="mensa['signups'] > 0">Inschrijven</div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import { PropType } from "vue";

    export default {
        props: {
            mensa: {
                type: Object as PropType<Mensa>,
                required: true
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
