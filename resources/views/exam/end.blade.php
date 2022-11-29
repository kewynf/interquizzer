<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Final Report - Exam ') }} #{{ $exam->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4 text-gray-800 dark:text-gray-200">

                <div class="flex flex-col">
                    <span>Exam #{{ $exam->id }}</span>
                    <span>Candidate: {{ $exam->candidate->name }}</span>
                    <span>Examiner: {{ $exam->user->name }}</span>
                </div>

                <div class="flex flex-col gap-4">
                    <span class="mb-4">Steps:</span>

                    @foreach ($exam->steps as $step)
                        <div class="flex flex-col gap-2">
                            <span>Step: {{ $step->title }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $step->description }}</span>

                            <div class="flex flex-col gap-4">
                                @foreach ($step->abilities as $ability)
                                    <div class="flex flex-col gap-1">
                                        <span>Ability: {{ $ability->title }}</span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">{{ $ability->description }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Grade:
                                            {{ $ability->grade }} / 10</span>

                                        <div class="flex flex-col gap-1">
                                            <span>Content Provided: {{ $ability->content_type }}
                                                ({{ $ability->content_title }})
                                            </span>
                                            <div>
                                                @switch($ability->content_type)
                                                    @case('text')
                                                        <span
                                                            class="w-full px-2 py-2 bg-slate-800 text-white rounded-md">{{ $ability->content_description }}</span>
                                                    @break

                                                    @case('image')
                                                        <span>{{ $ability->content_description }}</span>
                                                        <img src="{{ $ability->content_path }}" alt="">
                                                    @break

                                                    @case('video')
                                                        <span>{{ $ability->content_description }}</span>
                                                        <video src="{{ $ability->content_path }}" controls></video>
                                                    @break

                                                    @case('audio')
                                                        <span>{{ $ability->content_description }}</span>
                                                        <audio src="{{ $ability->content_path }}" controls></audio>
                                                    @break

                                                    @case('file')
                                                        <span>{{ $ability->content_description }}</span>
                                                        <a href="{{ $ability->content_path }}" target="_blank">Download</a>
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                        @if ($ability->answer_start_at && $ability->answer_end_at)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Answered in
                                                {{ $ability->answer_end_at->diffInSeconds($ability->answer_start_at) }}
                                                seconds
                                            </span>
                                        @endif

                                    </div>
                                    <div>
                                        <span>Comment:</span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ability->comment }}
                                        </p>
                                    </div>
                            </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

    </div>
    </div>
    </div>


</x-app-layout>
