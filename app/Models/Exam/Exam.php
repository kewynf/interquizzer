<?php

namespace App\Models\Exam;

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

    public function steps()
    {
        return $this->hasMany(ExamStep::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
