<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompressedCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compressed_copies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('media_id');
            $table->enum('type', ['sm', 'm', 'lg']);
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('media')
                ->onDelete('cascade');

            $table->unique(['media_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compressed_copies');
    }
}
