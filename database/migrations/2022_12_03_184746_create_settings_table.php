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
        Schema::create('settings', function (Blueprint $table) {

            $table->string('discord_api_url')->nullable();
            $table->string('discord_bot_token')->nullable();
            $table->string('discord_guild_id')->nullable();
            $table->string('discord_exam_channel_category_id')->nullable();

            $table->boolean('candidate_can_view_own_results')->default(false);

            $table->timestamps();
        });

        $settings = new \App\Models\Settings();
        $settings->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
