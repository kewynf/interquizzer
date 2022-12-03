<?php

namespace App\View\Components\Exam\During\Navigation;

use App\Models\Exam\Exam;
use Illuminate\View\Component;

class Element extends Component
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
        return view('components.exam.during.navigation.element');
    }
}
