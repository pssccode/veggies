<template>
    <div class="add__wrap">
        <div class="add__modal">
            <span class="close__button" @click="closeModal()">x</span>
            <div class="add__modal_header"></div>
            <div class="add__modal_body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Дата</label>
                        <br>
                        <date-range-picker
                            ref="picker"
                            :singleDatePicker="true"
                            :autoApply="true"
                            v-model="selectedDate"
                            :locale-data="locale"
                            :ranges="false">
                        </date-range-picker>
                    </div>
                    <div class="col-md-6">
                        <label for="">Культура</label>
                        <selector :params="cultures" v-model="selectedCulture"></selector>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Цена</label>
                            <input type="text" class="form-control" v-model="price">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Вес</label>
                            <input type="text" class="form-control" v-model="weight">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Сумма</label>
                            <input type="text" class="form-control" v-model="sum">
                        </div>
                    </div>
                </div>
            </div>
            <div class="add__modal_footer" @click="saveItem()">
                <p>Добавить</p>
            </div>
        </div>
    </div>
</template>

<script>
    import DateRangePicker from 'vue2-daterange-picker';
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';
    export default {
        components: { DateRangePicker },
        // props: ['date'],
        data(){
            return{
                cultures: [
                    {
                        name: 'Огурец',
                        number: '1'
                    },
                    {
                        name: 'Томат',
                        number: '2'
                    },
                    {
                        name: 'Перец болгарский',
                        number: '3'
                    },
                ],
                selectedCulture: {
                    name: 'Огурец',
                    number: '1'
                },
                price: 0,
                weight: 0,
                sum: 0,
                selectedDate: {
                    startDate: new Date(),
                    endDate: new Date()
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
                }

            }
        },
        watch:{
            price: function (val) {
                if(val && val > 0 && this.weight && this.weight > 0){
                    this.sum = (val * this.weight).toFixed(2);
                }
            },
            weight: function (val) {
                if(val && val > 0 && this.price && this.price > 0){
                    this.sum = (val * this.price).toFixed(2);
                }
            }
        },
        methods: {
            saveItem: function () {
                axios
                    .post('/store_sale', {
                        date: moment(this.selectedDate.startDate).format('yyyy-MM-DD'),
                        culture: this.selectedCulture.number,
                        price: this.price,
                        weight: this.weight,
                        sum: this.sum
                    })
                    .then(this.closeModal());
            },
            closeModal(){
                this.$root.$emit("closeItem", {});
            }
        }
    }
</script>

<style scoped>
    .add__wrap{
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 1000;
    }
    .add__modal{
        background-color: #fff;
        border-radius: 4px;
        position: relative;
        /*overflow: hidden;*/
    }

    @media screen and (max-width: 576px){
        .add__modal{
            width: 100%;
            margin: 10% auto;
            border-radius: 0;
            height: 555px;
        }
        .add__modal_footer{
            padding-top: 30px;
        }
    }
    @media screen and (min-width: 576px){
        .add__modal{
            width: 30%;
            margin: 10% auto;
            height: 400px;
        }
        .add__modal_footer{
            padding-top: 20px;
        }
    }
    .close__button{
        position: absolute;
        right: 0;
        top: 0;
        height: 30px;
        width: 30px;
        text-align: center;
        color: red;
        transition: all 1s;
        font-size: 20px;
        line-height: 20px;
        padding-top: 2px;
        border-top-right-radius: 4px;
    }
    .close__button:hover{
        background-color: red;
        color: #fff;
        cursor: pointer;
    }
    .add__modal_header{
        height: 10%;
    }
    .add__modal_body{
        height: 75%;
        padding: 15px;
    }
    .add__modal_footer{
        height: 15%;
        background-color: #16a085;
        text-align: center;
        color: #fff;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: bold;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .add__modal_footer:hover{
        cursor: pointer;
        background-color: #138E74;
        transition: all 1s;

    }
    .add__modal_footer p{
        margin-bottom: 0;
    }
    .vue-daterange-picker{
        width: 100%;
        max-width: unset;
    }
</style>
