<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['EQUITIES', 'DEBT SECURITIES', 'COMMODITIES', 'REAL ESTATE', 'PRIVATE EQUITY', 'ALTERNATIVE INVESTMENTS', 'CASH HOLDINGS']);
            $table->string('name');
            $table->decimal('holding', 12, 2);
            $table->decimal('market_value', 12, 2);
            $table->decimal('profit', 2, 2);
            $table->integer('sector_id');
            $table->integer('trading_block_id');
            $table->integer('country_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
