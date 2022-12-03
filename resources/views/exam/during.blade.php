<x-app-layout>
    <x-exam.during.ongoing-exam-alert />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4 overflow-y-auto">

                <x-exam.during.navigation.bar :exam="$exam" current="{{ $currentStep->id }}" />

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
