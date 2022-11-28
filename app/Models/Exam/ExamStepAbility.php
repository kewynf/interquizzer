<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStepAbility extends Model
{
    use HasFactory;

    protected $casts = [
        'answer_start_at' => 'datetime',
        'answer_end_at' => 'datetime',
    ];


    public $fillable = [
        'exam_step_id',
        'title',
        'description',
        'content_title',
        'content_description',
        'content_type',
        'content_path',
        'answer_start_at',
        'answer_end_at',
        'discord_message_id',
        'grade',
        'comment'
    ];
}
