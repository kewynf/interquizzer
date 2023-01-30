<?php

namespace App\Models\Exam;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'candidate_id',
        'title',
        'description',
        'icon',
        'discord_voice_channel_id',
        'discord_text_channel_id',
        'started_at',
        'ended_at',
    ];

    protected $appends = [
        'examiners',
        'invigilators',
        'candidates',
    ];

    public function steps()
    {
        return $this->hasMany(ExamStep::class);
    }

    public function abilities()
    {
        return $this->hasManyThrough(ExamStepAbility::class, ExamStep::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'exam_user')->withPivot('role');
    }

    public function getExaminersAttribute()
    {
        return $this->users()->wherePivot('role', 'examiner')->get();
    }

    public function getInvigilatorsAttribute()
    {
        return $this->users()->wherePivot('role', 'invigilator')->get();
    }

    public function getCandidatesAttribute()
    {
        return $this->users()->wherePivot('role', 'candidate')->get();
    }
}
