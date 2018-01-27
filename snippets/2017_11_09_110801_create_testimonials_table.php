<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned();
            $table->foreign('image_id')->references('id')->on('images');
            $table->string('name');
            $table->text('body');
            $table->integer('order');
            //allows ordering of entity if required
            $table->boolean('active')->default(false);
            //allows setting the entity to active to not to be able to filter
            $table->softDeletes();
            //allows soft deleting of entity/home/mushood/work/lovelife/resources/views/admin/testimonial
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
        Schema::dropIfExists('entities');
    }
}
