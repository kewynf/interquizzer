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
        Schema::create('collection_contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('collection_id')->references('id')->on('collections')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['text', 'image', 'video', 'audio', 'file']);
            $table->string('content_path')->nullable();

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
        Schema::dropIfExists('collection_contents');
    }
};
