<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFiledsIntoTheAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function($table) {
            $table->smallInteger('delta')->default(0)->after('market_value');
            $table->decimal('return_currency', 12, 2)->default(0)->after('profit');
            $table->smallInteger('return_percent')->default(0)->after('return_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function($table) {
            $table->dropColumn('delta');
            $table->dropColumn('return_currency');
            $table->dropColumn('return_percent');
        });
    }
}
