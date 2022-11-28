<?php

namespace App\Models\ExamTemplate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTemplate extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'title',
        'description',
        'icon',
    ];

    public function steps()
    {
        return $this->hasMany(ExamTemplateStep::class);
    }
}
