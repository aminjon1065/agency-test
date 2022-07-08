<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->json('files_link')->nullable();
            $table->boolean('opened')->default('0');
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('users');
            $table->unsignedBigInteger('to');
            $table->foreign('to')->references('id')->on('users');
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
        Schema::dropIfExists('messages');
    }
}
