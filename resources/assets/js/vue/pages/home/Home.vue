<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        {{ this.mensas }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import GetMensas from "../../../domain/mensa/usecase/GetMensas";
    import Mensa from "../../../domain/mensa/model/Mensa";
    import Vue from 'vue';

    export default Vue.extend({
        mounted() {
            GetMensas(20)
                .then(mensas => this.mensas = mensas)
                .catch(error => console.error(error))
        },
        data() {
            return {
                mensas: [] as Mensa[]
            }
        }
    });
</script>
