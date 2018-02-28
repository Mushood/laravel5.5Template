<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entitys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned();
            $table->foreign('image_id')->references('id')->on('images');
            $table->string('title');
            $table->text('body');
            $table->string('slug');
            $table->integer('order')->default(0);
            //allows ordering of entity if required
            $table->boolean('active')->default(false);
            //allows setting the entity to active to be able to filter
            $table->softDeletes();
            //allows soft deleting of entity
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
        Schema::dropIfExists('entitys');
    }
}
