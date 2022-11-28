<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStep extends Model
{
    use HasFactory;

    public $fillable = [
        'exam_id',
        'title',
        'description',
        'icon',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function abilities()
    {
        return $this->hasMany(ExamStepAbility::class);
    }
}
