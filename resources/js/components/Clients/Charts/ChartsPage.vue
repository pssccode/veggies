<template>
    <div class="history-page__wrap">
        <div class="container">
            <div>
                <filters-block v-model="filtersData" @input="getChart()"></filters-block>
            </div>

            <div class="row table__wrap">
                <div class="col-md-12">
                    <h3>Цены:</h3>
                    <graph-line
                        :height="400"
                        :shape="'normal'"
                        :axis-full-mode="true"
                        :labels="labels"
                        :names="names"
                        :values="prices">
                        <tooltip :names="names" :position="'right'"></tooltip>
                        <legends :names="names"></legends>
                        <guideline :tooltip-y="true"></guideline>
                    </graph-line>
                </div>
                <div class="col-md-12">
                    <h3>Вес:</h3>
                    <graph-line
                        :height="400"
                        :shape="'normal'"
                        :axis-full-mode="true"
                        :labels="labels"
                        :names="names"
                        :values="weight">
                        <tooltip :names="names" :position="'right'"></tooltip>
                        <legends :names="names"></legends>
                        <guideline :tooltip-y="true"></guideline>
                    </graph-line>
                </div>
                <div class="col-md-12">
                    <h3>Заработок:</h3>
                    <graph-line
                        :height="400"
                        :shape="'normal'"
                        :axis-full-mode="true"
                        :labels="labels"
                        :values="sum">
                        <guideline :tooltip-y="true"></guideline>
                    </graph-line>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import FiltersBlock from "../FiltersBlock";

    export default {
        components: {
            FiltersBlock
        },
        data() {
            const self = this;
            return {
                filtersData: {
                    year: {},
                    month: {},
                    culture: {},
                    date: {},
                },
                labels: [],
                prices: [],
                weight: [],
                sum: [],
                chartsData: []
            }
        },
        methods: {
            getChart() {
                var vm = this;
                vm.labels = [];
                vm.values = [];
                axios
                    .post('/get_charts', {
                        year: this.filtersData.year,
                        month: this.filtersData.month,
                        culture: this.filtersData.culture,
                        date: this.filtersData.date
                    })
                    .then(response => {
                        vm.prices = [response.data.prices];
                        vm.weight = [response.data.weights];
                        vm.sum = [response.data.sum];
                        vm.labels = response.data.dates;

                    });
            }
        },
        mounted() {
            this.getChart();
        }
    }
</script>

<style scoped>
    .table__wrap {
        margin-top: 20px;
    }

    .sales__info {
        margin-top: 80px;
        background-color: #e2e2e2;
        padding: 15px;
    }

    .dataTables_length {
        display: none !important;
    }

    .add__button {
        position: fixed;
        top: 30px;
        left: 30px;
        border-radius: 50%;
        height: 50px;
        width: 50px;
        border: none;
        background-color: #1ABC9C;
    }

    .date__title {
        display: block;
    }

    .vue-daterange-picker {
        width: 100%;
        max-width: unset;
    }
</style>

<style>
    .dataTables_length {
        display: none !important;
    }

    .add__button {
        z-index: 10000000000;
    }
</style>
