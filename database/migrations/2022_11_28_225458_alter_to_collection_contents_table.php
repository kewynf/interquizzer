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
        Schema::table('collection_contents', function (Blueprint $table) {
            $table->dropColumn('content_path');
            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_contents', function (Blueprint $table) {
            $table->dropColumn('path');
            $table->string('content_path');
        });
    }
};
