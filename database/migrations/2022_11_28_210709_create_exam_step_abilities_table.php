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
        Schema::create('exam_step_abilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_step_id')->constrained();

            $table->string('title');
            $table->text('description');

            $table->string('content_title')->nullable();
            $table->text('content_description')->nullable();
            $table->enum('content_type', ['text', 'image', 'video', 'audio', 'file'])->nullable();
            $table->string('content_path')->nullable();

            $table->timestamp('answer_start_at')->nullable();
            $table->timestamp('answer_end_at')->nullable();

            $table->string('discord_message_id')->nullable();

            $table->integer('grade')->nullable();
            $table->text('comment')->nullable();

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
        Schema::dropIfExists('exam_step_abilities');
    }
};
