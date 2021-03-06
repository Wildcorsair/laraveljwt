<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticHourlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_hourly', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('grip_date');
            $table->decimal('open', 18, 9);
            $table->decimal('high', 18, 9);
            $table->decimal('low', 18, 9);
            $table->decimal('last', 18, 9);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistic_hourly');
    }
}
