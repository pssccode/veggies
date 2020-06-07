<template>
    <div class="history-page__wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <span>Год:</span>
                    <selector :params="dataForSelectors.years" v-model="selectedYear"></selector>
                </div>
                <div class="col-md-3">
                    <span>Месяц:</span>
                    <selector :params="dataForSelectors.months" v-model="selectedMonth"></selector>
                </div>
                <div class="col-md-3">
                    <span>Культура:</span>
                    <selector :params="dataForSelectors.cultures" v-model="selectedCulture"></selector>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-primary btn-sm">Добавить</button>
                </div>
            </div>
            <div class="row table__wrap">
                <div class="col-md-12">
                    <vdtnet-table
                        ref="table"
                        :fields="fields"
                        :opts="options">
                    </vdtnet-table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VdtnetTable from 'vue-datatables-net';
    import 'datatables.net-bs4';
    export default {
        components: {
            VdtnetTable
        },
        data(){
            const self = this;
            return {
                selectedMonth: {
                    number: 0,
                    name: 'Все'
                },
                selectedYear: {
                    number: 0,
                    name: 'Все'
                },
                selectedCulture: {
                    number: 1,
                    name: 'Огурец'
                },
                dataForSelectors: {
                    months: {},
                    years: {}
                },
                fields: {
                    date: {label: 'Дата', sortable: true, searchable: false},
                    price: {label: 'Цена', sortable: true, searchable: true},
                    weight: {label: 'Вес', sortable: true, searchable: true},
                    sum: {label: 'Сумма', sortable: true, searchable: false},
                },
                options: {
                    ajax: {
                        'url': '/get_sales_history_table',
                        'type': 'POST',
                        "headers": {
                            'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN']
                        },
                        'data': function (d) {
                            d.year = self.selectedYear.number;
                            d.month = self.selectedMonth.number;
                            d.culture = self.selectedCulture.number;
                        }
                    },
                    responsive: true,
                    processing: true,
                    serverSide: true,
                },
            }
        },
        watch:{
            selectedYear: function (val) {
                this.getYearMonths(val.number);
                this.$refs.table.reload();
            },
            selectedMonth: function (val) {
                this.$refs.table.reload();
            }
        },
        methods: {
            getPayload: function () {
                axios
                .get('/get_history_selectors_payloads')
                .then(response => (this.dataForSelectors = response.data));
            },
            getYearMonths: function(year){
                axios
                    .get('/get_history_year_months'+'/'+this.selectedYear.number)
                    .then(response => (this.dataForSelectors.months = response.data));
            },
            getTable: function () {
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

<style scoped>
    .table__wrap{
        margin-top: 80px;
    }
</style>
