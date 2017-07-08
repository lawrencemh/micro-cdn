<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContainerIdToMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            // drop user_id constraint and column
            $table->dropForeign('media_user_id_foreign');
            $table->dropColumn('user_id');
            
            $table->integer('container_id')->unsigned()->index()->after('id');
            
            // Containers relation
            $table->foreign('container_id')->references('id')->on('containers')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_container_id_foreign');
            $table->dropColumn('container_id');
            
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }
}
