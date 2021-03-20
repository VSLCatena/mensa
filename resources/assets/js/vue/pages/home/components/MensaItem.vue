<template>
    <div class="container">
        <div class="row mt-3">
            <div class="col-8 col-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
