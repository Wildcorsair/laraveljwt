<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalFieldsIntoTheUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('country', 64)->nullable()->default('')->after('remember_token');
            $table->string('phone', 32)->nullable()->default('')->after('country');
            $table->string('address1')->nullable()->default('')->after('phone');
            $table->string('address2')->nullable()->default('')->after('address1');
            $table->string('city', 64)->nullable()->default('')->after('address2');
            $table->integer('postal_code')->nullable()->default(0)->after('city');
            $table->string('investor_type', 32)->nullable()->default('')->after('postal_code');
            $table->integer('tokens_count')->nullable()->default(0)->after('investor_type');
            $table->boolean('is_active')->default(false)->after('tokens_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('country');
            $table->dropColumn('phone');
            $table->dropColumn('address1');
            $table->dropColumn('address2');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
            $table->dropColumn('investor_type');
            $table->dropColumn('tokens_count');
            $table->dropColumn('is_active');
        });
    }
}
