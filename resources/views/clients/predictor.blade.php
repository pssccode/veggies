@extends('clients.layouts.main_template')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h3>Предсказываемый вес: {{ $weight }}кг</h3>
        </div>
        <div class="col-md-6">
            <h3>Предсказываемая цена: {{ $price }}грн.</h3>
        </div>
    </div>
</div>
@stop
