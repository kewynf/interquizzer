<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exam') }} #{{ $exam->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4 overflow-y-auto">

                <div
                    class=" flex justify-center items-center gap-4 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    @foreach ($exam->steps as $step)
                        <div class="text-xl text-center flex flex-col gap-1 text-gray-800 dark:text-gray-100">
                            <span>{{ $step->title }}
                                @if ($step->id == $currentStep->id)
                                    *
                                @endif
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $step->description }}</span>
                        </div>
                    @endforeach

                    <div>
                        <a href="{{ route('exam.end', $exam->id) }}">
                            FINALIZAR
                        </a>
                    </div>

                </div>

                <div
                    class=" flex justify-center items-center gap-4 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                    @foreach ($currentStep->abilities as $ability)
                        @livewire('exam.exam-step-ability', ['ability_id' => $ability->id], key($ability->id))
                    @endforeach


                </div>
                <div>
                    <a href="{{ route('exam.previousStep', [$exam->id, $currentStep->id]) }}">PREVIOUS</a>
                    <a href="{{ route('exam.nextStep', [$exam->id, $currentStep->id]) }}">NEXT</a>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>
