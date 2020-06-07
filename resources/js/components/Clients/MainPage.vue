<template>
    <div class="main-page__wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <culture-selector></culture-selector>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    07.06.20
                    <button class="btn btn-primary btn-flat">Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Журнал</h3>
                        </div>
                        <div class="col-md-12">
                            <h3>Виджеты</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <img src="clients/img/ogurets.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
