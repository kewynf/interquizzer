<?php

namespace App\Models\ExamTemplate;

use App\Models\Collection\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTemplateStepAbility extends Model
{
    use HasFactory;

    public $fillable = [
        'exam_template_step_id',
        'title',
        'description',
        'icon',
        'order',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
