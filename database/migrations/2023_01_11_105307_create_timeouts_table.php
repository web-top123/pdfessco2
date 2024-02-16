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
        Schema::create('timeouts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->integer('timeout')->default(60);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::disableForeignKeyConstraints();


        if(Schema::hasTable('timeouts')){

            Schema::table('timeouts', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });

        }

        Schema::dropIfExists('timeouts');

        Schema::enableForeignKeyConstraints();
    }
};
