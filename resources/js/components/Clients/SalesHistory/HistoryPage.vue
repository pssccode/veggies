<template>
    <div class="history-page__wrap">
        <add-sale-modal v-show="showAddModal"></add-sale-modal>
        <div class="container">
            <div>
                <filters-block v-model="filtersData" @input="updateTable()"></filters-block>
                <button class="add__button" @click="showAddModal = true">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="sales__info">
                        <div class="row">
                            <div class="col-md-4">
                                Общий вес: <strong>{{ allWeight }}</strong>
                            </div>
                            <div class="col-md-4">
                                Общий заработок: <strong>{{ allSum }}</strong>
                            </div>
                            <div class="col-md-4">
                                Всего сборок: <strong>{{ pickingCnt }}</strong>
                            </div>
                            <div class="col-md-4">
                                Мин. цена: <strong>{{ minPrice }}</strong>
                            </div>
                            <div class="col-md-4">
                                Средняя цена: <strong>{{ avPrice }}</strong>
                            </div>
                            <div class="col-md-4">
                                Макс. цена: <strong>{{ maxPrice }}</strong>
                            </div>
                            <div class="col-md-4">
                                Мин. вес: <strong>{{ minWeight }}</strong>
                            </div>
                            <div class="col-md-4">
                                Средний вес: <strong>{{ avWeight }}</strong>
                            </div>
                            <div class="col-md-4">
                                Макс. вес: <strong>{{ maxWeight }}</strong>
                            </div>
                            <div class="col-md-4">
                                Мин. заработок: <strong>{{ minSum }}</strong>
                            </div>
                            <div class="col-md-4">
                                Средний заработок: <strong>{{ avSum }}</strong>
                            </div>
                            <div class="col-md-4">
                                Макс. заработок: <strong>{{ maxSum }}</strong>
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
    import FiltersBlock from "../FiltersBlock";
    export default {
        components: {
            FiltersBlock,
            VdtnetTable
        },
        data(){
            const self = this;
            return {
                fields: {
                    date: {label: 'Дата', sortable: true, searchable: false},
                    price: {label: 'Цена', sortable: true, searchable: true},
                    weight: {label: 'Вес', sortable: true, searchable: true},
                    sum: {label: 'Сумма', sortable: true, searchable: false},
                },
                filtersData: {
                    year: {},
                    month: {},
                    culture: {},
                    date: {},
                },
                options: {
                    ajax: {
                        'url': '/get_sales_history_table',
                        'type': 'POST',
                        "headers": {
                            'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN']
                        },
                        'data': function (d) {
                            d.year = self.filtersData.year;
                            d.month = self.filtersData.month;
                            d.culture = self.filtersData.culture;
                            d.date = self.filtersData.date;
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
                avWeight: 0,
                minSum: 0,
                maxSum: 0,
                avSum: 0,
                pickingCnt: 0,
                allPrice: 0,
                avPrice: 0,
            }
        },
        methods: {
            updateTable: function(){
                try{
                    this.$refs.table.reload();
                }catch(err){}
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
                    vdtnet.dataTable.on('xhr.dt',  function (e, settings, json, xhr) {
                        self.updateSummary(json);
                    });
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
                if(data && data.max_weight){
                    this.avWeight = data.picking_cnt > 0 ? (data.weight / data.picking_cnt).toFixed(2)+"кг" : 0;
                }
                if(data && data.min_sum){
                    this.minSum = data.min_sum+'грн.';
                }
                if(data && data.max_sum){
                    this.maxSum = data.max_sum+'грн.';
                }
                if(data && data.max_sum){
                    this.avSum = data.picking_cnt > 0 ? (data.sum / data.picking_cnt).toFixed(2)+"грн." : 0;
                }
                if(data && data.picking_cnt){
                    this.pickingCnt = data.picking_cnt;
                }
                if(data && data.all_price){
                    this.avPrice = data.picking_cnt > 0 ? (data.all_price / data.picking_cnt).toFixed(2)+"грн." : 0;
                }
            }
        },
        mounted() {
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
    .add__button{
        position: fixed;
        top: 30px;
        left: 30px;
        border-radius: 50%;
        height: 50px;
        width: 50px;
        border: none;
        background-color: #1ABC9C;
    }
    .date__title{
        display: block;
    }
    .vue-daterange-picker{
        width: 100%;
        max-width: unset;
    }
</style>

<style>
    .dataTables_length{
        display: none !important;
    }
    .add__button{
        z-index: 10000000000;
    }
</style>
