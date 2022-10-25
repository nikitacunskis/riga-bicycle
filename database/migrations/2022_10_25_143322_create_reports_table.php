<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('event_id');
            $table->integer('womens');
            $table->integer('man');
            $table->integer('radway');
            $table->integer('pavement');
            $table->integer('biekpath');
            $table->integer('child_chairs');
            $table->integer('supermobility');
            $table->integer('to_center');
            $table->integer('from_center');
            $table->integer('children_self');
            $table->integer('children_passanger');
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
