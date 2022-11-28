<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exam') }} #{{ $exam->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4">

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
                        <div class="text-xl text-center flex flex-col gap-1 text-gray-800 dark:text-gray-100">
                            <span>{{ $ability->title }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $ability->description }}</span>

                            @if ($ability->content_type)
                                <div class="flex flex-col gap-2">
                                    <span>Conteúdo: {{ $ability->content_type }}</span>

                                    @switch($ability->content_type)
                                        @case('text')
                                            <span
                                                class="w-full px-2 py-2 bg-slate-800 text-white rounded-md">{{ $ability->content_description }}</span>
                                        @break

                                        @case('image')
                                            <img src="{{ $ability->content_path }}" alt="">
                                        @break

                                        @case('video')
                                            <video src="{{ $ability->content_path }}" controls></video>
                                        @break

                                        @case('audio')
                                            <audio src="{{ $ability->content_path }}" controls></audio>
                                        @break

                                        @case('file')
                                            <a href="{{ $ability->content_path }}" target="_blank">Download</a>
                                        @break
                                    @endswitch

                                </div>
                            @endif

                            <div>
                                <button class="bg-green-600 text-white">
                                    SEND CONTENT AND START TIMER
                                </button>

                                <button class="bg-red-500 text-white">
                                    STOP TIMER
                                </button>

                            </div>

                            <div>
                                <label for="">Grade</label>
                                <input type="number" min='0' max='10'>
                            </div>

                            <div>
                                <label for="">Observation</label>
                                <textarea name="" id="" class="w-full h-1/2"></textarea>
                            </div>
                        </div>
                    @endforeach


                </div>
                <div>
                    <button>NEXT</button>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>
