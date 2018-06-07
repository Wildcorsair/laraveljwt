<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('email', 64);
            $table->string('residence');
            $table->text('message');
            $table->boolean('seed_investor');
            $table->boolean('service_provider');
            $table->boolean('retail_investor');
            $table->boolean('institutional');
            $table->boolean('government');
            $table->boolean('media');
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
        Schema::dropIfExists('contact_messages');
    }
}
