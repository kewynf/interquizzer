<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Final Report - Exam ') }} #{{ $exam->id }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-gray-800 dark:text-gray-200">

            <pre class="bg-gray-800 text-white px-4 py-2 w-full break">
Exam #{{ $exam->id }} - {{ $exam->title }}
Started at: {{ $exam->started_at }} - Ended at: {{ $exam->ended_at }}
Candidates: @foreach ($exam->candidates as $candidate)
{{ $candidate->name }} @if (!$loop->first)
,
@endif
@endforeach

Examiners: @foreach ($exam->examiners as $examiner)
{{ $examiner->name }} @if (!$loop->first)
,
@endif
@endforeach

Invigilators: @foreach ($exam->invigilators as $invigilator)
{{ $invigilator->name }} @if (!$loop->first)
,
@endif
@endforeach


Steps: 
--------------------
@foreach ($exam->steps as $step)
- {{ $step->title }}
  {{ $step->description }}
Abilities:
@foreach ($step->abilities as $ability)
- {{ $ability->title }}
    {{ $ability->description }}
    Grade: {{ $ability->grade }} / 10
    Observations: {{ $ability->observations }}
    Content Provided: {{ __('contentType.' . $ability->content_type) }} ({{ $ability->content_title }})
                      {{ $ability->content_description }}
                      Path to Content: <a target="_blank" href="{{ $ability->content_path }}" class="underline">CLICK HERE</a>
@endforeach
--------------------
@endforeach

AVERAGE GRADE: {{ round($exam->abilities->avg('grade'), 1) }} / 10

THANKS FOR USING REPORTIK!
</pre>


            <a href="{{ route('exam.discord.delete', $exam->id) }}" class="px-4 py-2 text-white bg-red-500">DELETE
                DISCORD
                CHANNELS</a>
        </div>
    </div>



</x-app-layout>
