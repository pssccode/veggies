<template>
    <div class="row">
        <div class="col-md-3 pr">
            <strong class="date__title">Дата:</strong>
            <date-range-picker
                ref="picker"
                :autoApply="true"
                v-model="selectedDate"
                :locale-data="locale"
                :ranges="false">
            </date-range-picker>
            <button class="reset-date__button" @click="resetDate">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="col-md-3">
            <strong>Год:</strong>
            <selector :params="dataForSelectors.years" v-model="selectedYear"></selector>
        </div>
        <div class="col-md-3">
            <strong>Месяц:</strong>
            <selector :params="dataForSelectors.months" v-model="selectedMonth"></selector>
        </div>
        <div class="col-md-3">
            <strong>Культура:</strong>
            <selector :params="dataForSelectors.cultures" v-model="selectedCulture"></selector>
        </div>
        <button class="add__button" @click="showAddModal = true">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</template>
<script>
    import DateRangePicker from 'vue2-daterange-picker';
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

    export default {
        props: ['value'],
        components: {
            DateRangePicker
        },
        data() {
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
                selectedDate: {},
                selectedDateFormat: {
                    startDate: '',
                    endDate: ''
                },
                result: {
                    month: {},
                    year: {},
                    culture: {},
                    date: {},
                },
                locale: {
                    firstDay: 1,
                    direction: 'ltr',
                    format: 'dd.mm.yyyy',
                    separator: ' - ',
                    applyLabel: 'Применить',
                    cancelLabel: 'Отмена',
                    weekLabel: 'W',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                },
                disableInputs: false
            }
        },
        watch: {
            selectedYear: function (val) {
                this.getYearMonths(val.number);
                this.initResults();
            },
            selectedMonth: function (val) {
                this.initResults();
            },
            selectedDate: function (val) {
                this.disableInputs = false;
                if (val && (val.startDate !== null && val.endDate !== null)) {
                    this.selectedDateFormat.startDate = moment(val.startDate).format('YYYY-MM-DD');
                    this.selectedDateFormat.endDate = moment(val.endDate).format('YYYY-MM-DD');
                    this.disableInputs = true;
                }else{
                    this.selectedDateFormat.startDate = '';
                    this.selectedDateFormat.endDate = '';
                }
                this.initResults();
            }
        },
        methods: {
            resetDate: function () {
                this.selectedDate = {startDate: null, endDate: null};
            },
            initResults: function () {
                this.result.month = this.selectedMonth.number;
                this.result.year = this.selectedYear.number;
                this.result.culture = this.selectedCulture.number;
                this.result.date = this.selectedDateFormat;
                this.$emit('input', this.result);
            },
            getPayload: function () {
                axios
                    .get('/get_history_selectors_payloads')
                    .then(response => (this.dataForSelectors = response.data));
                this.initResults();
            },
            getYearMonths: function (year) {
                axios
                    .get('/get_history_year_months' + '/' + this.selectedYear.number)
                    .then(response => (this.dataForSelectors.months = response.data));
            },
        },
        mounted() {
            this.getPayload();
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

    .reset-date__button {
        position: absolute;
        top: 30px;
        right: 20px;
        border-radius: 50%;
        height: 20px;
        width: 20px;
        border: none;
        background-color: red;
        color: #fff;
        text-align: center;
        padding: 0 0 0 1px;
        line-height: 20px;
    }

    .reset-date__button:hover {
        cursor: pointer;
    }
</style>

<style>
    .dataTables_length {
        display: none !important;
    }

    .pr {
        position: relative;
    }
</style>
