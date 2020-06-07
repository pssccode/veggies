<template>
    <div class="selector__wrap" @click="showItems = !showItems">
        <p>{{ value.name }}</p>
        <ul class="selector__items" v-show="showItems">
            <li v-show="value.number > 0" @click="setItem(0, 'Все')">Все</li>
            <li v-for="param in params" @click="setItem(param.number, param.name)">{{ param.name }}</li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: ['params', 'value'],
        data() {
            return {
                showItems: false
            }
        },
        methods: {
            setItem: function (number, name) {
                this.value = {
                    number: number,
                    name: name,
                };
                this.showItems = false;
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
    }

    p {
        margin: 0;
    }

    .selector__items {
        list-style: none;
        position: absolute;
        left: 0;
        border: 1px solid red;
        width: 100%;
        margin-left: 0;
        padding-left: 0;
    }
</style>
