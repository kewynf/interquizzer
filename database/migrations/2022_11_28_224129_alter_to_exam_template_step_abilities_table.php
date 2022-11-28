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
        Schema::table('exam_template_step_abilities', function (Blueprint $table) {
            //Drop Foreign exam_step_id
            $table->dropForeign('exam_template_step_abilities_exam_step_id_foreign');
            //Drop exam_step_id
            $table->dropColumn('exam_step_id');
            //Add exam_template_step_id
            $table->foreignId('exam_template_step_id')->constrained('exam_template_steps')->cascadeOnDelete()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_template_step_abilities', function (Blueprint $table) {
            //Drop Foreign exam_template_step_id
            $table->dropForeign('exam_template_step_abilities_exam_template_step_id_foreign');
            //Drop exam_template_step_id
            $table->dropColumn('exam_template_step_id');
            //Add exam_step_id
            $table->foreignId('exam_step_id')->constrained('exam_steps')->cascadeOnDelete()->after('id');
        });
    }
};
