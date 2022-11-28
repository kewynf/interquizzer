<?php

namespace App\Http\Controllers;

use App\Models\Exam\Candidate;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamStep;
use App\Models\Exam\ExamStepAbility;
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

        foreach ($template->steps as $step) {
            $examStep = [
                'exam_id' => $exam->id,

                'title' => $step->title,
                'description' => $step->description,
                'icon' => $step->icon,
            ];

            $examStep = ExamStep::create($examStep);

            foreach ($step->abilities as $ability) {
                if ($ability->collection_id) {
                    $content = $ability->collection->contents->random();

                    $examStepAbility = [
                        'exam_step_id' => $examStep->id,

                        'title' => $ability->title,
                        'description' => $ability->description,

                        'content_title' => $content->title,
                        'content_description' => $content->description,
                        'content_type' => $content->type,
                        'content_path' => $content->path,
                    ];
                } else
                    $examStepAbility = [
                        'exam_step_id' => $examStep->id,

                        'title' => $ability->title,
                        'description' => $ability->description,
                    ];

                $examStepAbility = ExamStepAbility::create($examStepAbility);
            }
        }

        $exam->refresh();
        return $exam;
    }
}
