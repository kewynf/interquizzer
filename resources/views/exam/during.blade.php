<x-app-layout>
    <x-exam.during.ongoing-exam-alert />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4 overflow-y-auto">

                <x-exam.during.navigation.bar :exam="$exam" current="{{ $currentStep->id }}" />

                <div class="flex flex-col justify-center items-center gap-16 p-4 sm:p-8 shadow sm:rounded-lg">

                    @foreach ($currentStep->abilities as $ability)
                        @livewire('exam.exam-step-ability', ['ability_id' => $ability->id], key($ability->id))
                    @endforeach



                </div>
                <div class="flex justify-between">
                    <a href="{{ route('exam.previousStep', [$exam->id, $currentStep->id]) }}"
                        class="bg-green-500 text-white px-4 py-2 rounded">
                        << PREVIOUS</a>
                            <a href="{{ route('exam.nextStep', [$exam->id, $currentStep->id]) }}"
                                class="bg-green-500 text-white px-4 py-2 rounded">NEXT >></a>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>
