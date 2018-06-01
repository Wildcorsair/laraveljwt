<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsIntoTheTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team', function($table) {
            $table->string('github')->nullable()->default('')->after('twitter');
            $table->string('stack_overflow')->nullable()->default('')->after('github');
            $table->text('description')->nullable()->after('stack_overflow');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team', function($table) {
            $table->dropColumn('github');
            $table->dropColumn('stack_overflow');
            $table->dropColumn('description');
        });
    }
}
