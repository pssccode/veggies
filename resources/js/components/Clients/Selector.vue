<template>
    <div>
        <div class="selector-page__wrap" v-show="showItems"  @click="showItems = !showItems">
        </div>
        <div class="selector__wrap">
            <p @click="showItems = !showItems">{{ selectedItem.name }}</p>
            <ul class="selector__items" v-show="showItems">
                <li v-show="showAll && selectedItem.number > 0" @click="setItem(0, 'Все')">Все</li>
                <li v-for="param in params" @click="setItem(param.number, param.name)">{{ param.name }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['params', 'value', 'showAll'],
        data() {
            return {
                showItems: false,
                selectedItem: this.value
            }
        },
        methods: {
            setItem: function (number, name) {
                this.selectedItem = {
                    number: number,
                    name: name,
                };
                this.showItems = false;
                this.$emit('input', this.selectedItem);
            }
        }
    }
</script>

<style scoped>
    .selector__wrap {
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        padding: 5px 10px;
        position: relative;
        z-index: 99999;
    }

    p {
        margin: 0;
    }

    .selector__items {
        list-style: none;
        position: absolute;
        left: 0;
        width: 100%;
        margin-left: 0;
        padding-left: 0;
        border-radius: 5px;
        -webkit-box-shadow: 2px 2px 11px 0px rgba(50, 50, 50, 0.27);
        -moz-box-shadow:    2px 2px 11px 0px rgba(50, 50, 50, 0.27);
        box-shadow:         2px 2px 11px 0px rgba(50, 50, 50, 0.27);
        background-color: #fff;
    }
    .selector__items li{
        padding: 10px;
        border-bottom: 1px solid #E5E5E5;
        transition: all 1s;
    }
    .selector__items li:last-child{
        border-bottom: none;
    }
    .selector__wrap:hover,
    .selector__items li:hover{
        cursor: pointer;
    }
    .selector__items li:hover{
        background-color: #E5E5E5;
    }
    .selector-page__wrap{
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 100vw;
        z-index: 9999;
    }
</style>
