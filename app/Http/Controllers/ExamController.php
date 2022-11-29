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

    public function renderExam(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        return view('exam.exam', [
            'exam' => $exam,
        ]);
    }

    public function createDiscordChannel(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        DiscordController::createExamChannels($exam);

        return redirect()->back();
    }

    public function start(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        if (is_null($exam->started_at)) {
            $exam->started_at = now();
            $exam->save();
        }


        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $exam->steps->first()->id]);
    }

    public function during(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        $step = ExamStep::findOrFail($step_id);

        if ($step->exam->id !== $exam->id) {
            abort(403);
        }

        return view('exam.during', [
            'exam' => $exam,
            'currentStep' => $step,
        ]);
    }

    public function previousStep(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        $currentStep = ExamStep::findOrFail($step_id);

        if ($currentStep->exam->id !== $exam->id) {
            abort(403);
        }

        $flag = false;

        foreach ($exam->steps->sortByDesc('id') as $step) {
            if ($flag) {
                return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $step->id]);
            }

            if ($step->id === $currentStep->id) {
                $flag = true;
            }
        }

        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $currentStep->id]);
    }

    public function nextStep(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        $currentStep = ExamStep::findOrFail($step_id);

        if ($currentStep->exam->id !== $exam->id) {
            abort(403);
        }

        $flag = false;

        foreach ($exam->steps as $step) {
            if ($flag) {
                return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $step->id]);
            }

            if ($step->id === $currentStep->id) {
                $flag = true;
            }
        }

        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $currentStep->id]);
    }

    public function end(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        if ($exam->user->id !== auth()->user()->id) {
            abort(403);
        }

        if (is_null($exam->ended_at)) {
            $exam->ended_at = now();
            $exam->save();
        }

        return view('exam.end', [
            'exam' => $exam,
        ]);
    }

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
