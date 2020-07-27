<template>
    <div class="history-page__wrap">
        <div class="container" v-if="!showResults">
            <div class="row table__wrap">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <strong>Культура:</strong>
                    <selector :params="dataForSelectors.cultures" v-model="selectedCulture"
                              :show-all="false"></selector>
                    <strong class="date__title">Дата:</strong><br>
                    <date-range-picker
                        ref="picker"
                        :autoApply="true"
                        :singleDatePicker="true"
                        v-model="defaultDate"
                        :locale-data="locale"
                        :ranges="false">
                    </date-range-picker>
                    <button class="btn btn-success mt20" @click="getResults()">Сравнить</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3>Даты для сравнения: </h3>
                </div>
            </div>
            <div class="row table__wrap">
                <div class="col-md-3">
                    <div class="comp__wrap">
                        <p>Дата №1</p>
                        <date-range-picker
                            ref="picker"
                            :autoApply="true"
                            :singleDatePicker="true"
                            v-model="selectedDate[itemsCnt]"
                            :locale-data="locale"
                            :ranges="false">
                        </date-range-picker>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="comp__wrap">
                        <button class="add__button" @click="addDate()">Добавить дату</button>
                    </div>
                </div>
                <div class="col-md-3" v-show="itemsCnt > 0 && key > 0" v-for="(item, key) in selectedDate">
                    <div class="comp__wrap">
                        <p>Дата №{{ key + 1 }}</p>
                        <date-range-picker
                            ref="picker"
                            :autoApply="true"
                            :singleDatePicker="true"
                            v-model="selectedDate[key]"
                            :locale-data="locale"
                            :ranges="false">
                        </date-range-picker>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" v-else>
            <div class="row table__wrap">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import DateRangePicker from 'vue2-daterange-picker';
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';

    export default {
        components: {
            DateRangePicker
        },
        data() {
            return {
                showResults: false,
                itemsCnt: 0,
                selectedCulture: {
                    number: 1,
                    name: 'Огурец'
                },
                dataForSelectors: {
                    months: {},
                    years: {}
                },
                defaultDate: {
                    startDate: new Date(),
                    endDate: new Date()
                },
                selectedDate: [
                    {
                        startDate: new Date(),
                        endDate: new Date()
                    }
                ],
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
            }
        },
        methods: {
            getPayload: function () {
                axios
                    .get('/get_history_selectors_payloads')
                    .then(response => (this.dataForSelectors = response.data));
            },
            addDate: function() {
                this.itemsCnt++;
                this.selectedDate.push({
                    startDate: new Date(),
                    endDate: new Date()
                });
            },
            getResults(){
                axios
                    .post('/get_comparison', {
                        dates: this.selectedDate,
                        date: this.defaultDate,
                        culture: this.selectedCulture.number
                    })
                    .then(response => {

                    });
                this.showResults = true;
            }
        },
        mounted() {
            this.getPayload();
        }
    }
</script>

<style scopped>
    .vue-daterange-picker {
        width: 100%;
    }

    .comp__wrap {
        border: 1px solid #ccc;
        width: 100%;
        height: 120px;
        padding: 20px;
        margin-top: 10px;
    }

    .add__button {
        background-color: rgba(50, 168, 0, 0.5);
        width: 100%;
        height: 100%;
        display: block;
        border: none;
        color: #fff;
        font-size: 25px;
    }
</style>
