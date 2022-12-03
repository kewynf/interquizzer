<?php

namespace App\View\Components\Exam\Start;

use App\Models\Exam\Exam;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public Exam $exam;
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.exam.start.header');
    }
}
