<template>
    <div class="history-page__wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <selector :params="dataForSelectors.months" v-model="selectedMonth"></selector>
                </div>
                <div class="col-md-3">
<!--                    <selector></selector>-->
                </div>
                <div class="col-md-3">
                    Огурец
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                selectedMonth: {
                    number: 0,
                    name: 'Все'
                },
                dataForSelectors: {
                    months: {},
                    years: {}
                }
            }
        },
        methods: {
            getPayload: function () {
                axios
                .get('/get_history_selectors_payloads')
                .then(response => (this.dataForSelectors = response.data));
            }
        },
        mounted() {
            this.getPayload();
        }
    }
</script>
