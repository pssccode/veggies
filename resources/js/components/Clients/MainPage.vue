<template>
    <div class="main-page__wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Продукт:</strong></p>
                    <selector :params="dataForSelectors.cultures" v-model="selectedCulture"
                              :show-all="showAll"></selector>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    07.06.20
                    <button class="btn btn-primary btn-flat">Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Журнал</h3>
                            <short-journal :journal="journal"></short-journal>
                        </div>
                        <div class="col-md-12">
                            <h3>Виджеты</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <img src="clients/img/ogurets.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            const self = this;
            return {
                selectedCulture: {
                    number: 1,
                    name: 'Огурец'
                },
                dataForSelectors: {
                    months: {},
                    years: {}
                },
                showAll: true,
                journal: []
            }
        },
        methods: {
            getPayload: function () {
                axios
                    .get('/get_history_selectors_payloads')
                    .then(response => (this.dataForSelectors = response.data));
                this.getJournal();
            },
            getJournal: function () {
                axios
                    .post('/get_short_journal')
                    .then(response => (this.journal = response.data));
            }
        },
        mounted() {
            this.getPayload();
        }
    }
</script>
