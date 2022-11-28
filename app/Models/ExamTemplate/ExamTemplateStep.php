<?php

namespace App\Models\ExamTemplate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTemplateStep extends Model
{
    use HasFactory;

    public $fillable = [
        'exam_template_id',
        'title',
        'description',
        'icon',
        'order',
    ];

    public function abilities()
    {
        return $this->hasMany(ExamTemplateStepAbility::class);
    }
}
