<?php

namespace App\Http\Livewire\Exam;

use App\Models\Exam\ExamStepAbility as ExamExamStepAbility;
use Livewire\Component;

class ExamStepAbility extends Component
{

    public ExamExamStepAbility $ability;
    public int $ability_id;

    public $grade;
    public $comment;

    public function mount(int $ability_id)
    {
        $this->ability_id = $ability_id;
        $this->ability = ExamExamStepAbility::find($ability_id);

        $this->grade = $this->ability->grade;
        $this->comment = $this->ability->comment;
    }

    public function saveGrade()
    {
        $this->ability->grade = $this->grade;
        $this->ability->save();
    }

    public function saveComment()
    {
        $this->ability->comment = $this->comment;
        $this->ability->save();
    }

    public function sendDiscordMessage()
    {
    }

    public function startTimer()
    {
        $this->ability->answer_start_at = now();
        $this->ability->save();
    }

    public function stopTimer()
    {
        $this->ability->answer_end_at = now();
        $this->ability->save();
    }

    public function resetTimer()
    {
        $this->ability->answer_start_at = null;
        $this->ability->answer_end_at = null;
        $this->ability->save();
    }

    public function render()
    {
        return view('livewire.exam.exam-step-ability', [
            'ability' => ExamExamStepAbility::find($this->ability_id),
        ]);
    }
}
