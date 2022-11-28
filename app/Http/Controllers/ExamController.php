<?php

namespace App\Http\Controllers;

use App\Models\Exam\Candidate;
use App\Models\Exam\Exam;
use App\Models\ExamTemplate\ExamTemplate;
use App\Models\User;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function generate(ExamTemplate $template, User $user, Candidate $candidate, string $discord_voice_channel_id, string $discord_text_channel_id)
    {
        $exam = [
            'user_id' => $user->id,
            'candidate_id' => $candidate->id,

            'title' => $template->title,
            'description' => $template->description,
            'icon' => $template->icon,

            'discord_voice_channel_id' => $discord_voice_channel_id,
            'discord_text_channel_id' => $discord_text_channel_id,
        ];

        $exam = Exam::create($exam);
    }
}
