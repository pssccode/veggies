<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCulturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cultures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img')->nullable();
        });

        \App\Models\Culture::insert([
            [
                'name' => 'Огурец',
                'img' => 'ogurets.svg'
            ],
            [
                'name' => 'Томат',
                'img' => null
            ],
            [
                'name' => 'Перец болгарский',
                'img' => null
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cultures');
    }
}
