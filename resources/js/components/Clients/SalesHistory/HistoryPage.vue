<template>
    <div class="history-page__wrap">
        <add-sale-modal v-show="showAddModal"></add-sale-modal>
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
                    <button class="btn btn-primary btn-sm" @click="showAddModal = true">
                        <i class="fa fa-plus"></i>Добавить
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="sales__info">
                        <div class="row">
                            <div class="col-md-4">
                                Общий вес: {{ allWeight }}
                            </div>
                            <div class="col-md-4">
                                Общий заработок: {{ allSum }}
                            </div>
                            <div class="col-md-4">
                                Минимальная/Максимальная цена: {{ minPrice+'/'+maxPrice }}
                            </div>
                            <div class="col-md-6">
                                Минимальный/Максимальный вес: {{ minWeight+'/'+maxWeight }}
                            </div>
                            <div class="col-md-6">
                                Минимальный/Максимальный заработок: {{ minSum+'/'+maxSum }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row table__wrap">
                <div class="col-md-12">
                    <vdtnet-table
                        ref="table"
                        :fields="fields"
                        :opts="options"
                        @table-created="tableLoaded"
                        @reloaded="tableLoaded">
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
                showAddModal: false,
                allWeight: 0,
                allSum: 0,
                minPrice: 0,
                maxPrice: 0,
                minWeight: 0,
                maxWeight: 0,
                minSum: 0,
                maxSum: 0
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
            },
            closeModal: function (){
                this.showAddModal = false;
                setTimeout(() => this.$refs.table.reload(), 300);
            },
            tableLoaded: function (vdtnet) {
                let self = this;
                if(vdtnet.dataTable){
                    vdtnet.dataTable.on( 'xhr.dt',  function (e, settings, json, xhr) {
                        self.updateSummary(json);
                    } );
                }
            },
            updateSummary(data){
                if(data && data.sum){
                    this.allSum = data.sum+'грн.';
                }
                if(data && data.weight){
                    this.allWeight = data.weight+'кг';
                }
                if(data && data.min_price){
                    this.minPrice = data.min_price+'грн.';
                }
                if(data && data.max_price){
                    this.maxPrice = data.max_price+'грн.';
                }
                if(data && data.min_weight){
                    this.minWeight = data.min_weight+'кг';
                }
                if(data && data.max_weight){
                    this.maxWeight = data.max_weight+'кг';
                }
                if(data && data.min_sum){
                    this.minSum = data.min_sum+'грн.';
                }
                if(data && data.max_sum){
                    this.maxSum = data.max_sum+'грн.';
                }
            }
        },
        mounted() {
            this.getPayload();
            this.$root.$on('closeItem', opt => this.closeModal());
        }
    }
</script>

<style scoped>
    .table__wrap{
        margin-top: 20px;
    }
    .sales__info{
        margin-top: 80px;
        background-color: #e2e2e2;
        padding: 15px;
    }
    .dataTables_length{
        display: none !important;
    }
</style>

<style>
    .dataTables_length{
        display: none !important;
    }
</style>
