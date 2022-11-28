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
        Schema::create('exam_template_step_abilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_step_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('description');

            $table->foreignId('collection_id')->constrained()->onDelete('restrict');

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
        Schema::dropIfExists('exam_template_step_abilities');
    }
};
