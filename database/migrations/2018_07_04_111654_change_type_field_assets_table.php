<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeFieldAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->integer('type_id')->after('profit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->enum('type', ['EQUITIES', 'DEBT SECURITIES', 'COMMODITIES', 'REAL ESTATE', 'PRIVATE EQUITY', 'ALTERNATIVE INVESTMENTS', 'CASH HOLDINGS'])->after('id');
        });
    }
}
