<template>
    <div class="selector__wrap">
        <p @click="showItems = !showItems">{{ selectedItem.name }}</p>
        <ul class="selector__items" v-show="showItems">
            <li v-show="selectedItem.number > 0" @click="setItem(0, 'Все')">Все</li>
            <li v-for="param in params" @click="setItem(param.number, param.name)">{{ param.name }}</li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: ['params', 'value'],
        data() {
            return {
                showItems: true,
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
        z-index: 1;
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
        z-index: 2000;
    }
</style>
