<template>
    <div class="short-journal__wrap">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Продукт</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in journal">
                    <td>{{ item.date }}</td>
                    <td>{{ item.culture.name }}</td>
                    <td>{{ item.price }}</td>
                    <td>{{ item.sum }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['journal'],
        data() {
            const self = this;
            return {
                // selectedCulture: {
                //     number: 1,
                //     name: 'Огурец'
                // },
                // dataForSelectors: {
                //     months: {},
                //     years: {}
                // },
                // showAll: true
            }
        },
        methods: {
            // getPayload: function () {
            //     axios
            //         .get('/get_history_selectors_payloads')
            //         .then(response => (this.dataForSelectors = response.data));
            // }
        },
        mounted() {
        }
    }
</script>

<style scoped>
    .short-journal__wrap{
        max-height: 30vh;
        overflow-y: auto;
    }
</style>
