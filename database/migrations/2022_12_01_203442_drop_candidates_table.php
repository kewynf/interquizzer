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
        //Drop Candidates Table
        Schema::dropIfExists('candidates');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Rebuild Candidates Table
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('discord_id')->nullable();

            $table->timestamps();
        });
    }
};
