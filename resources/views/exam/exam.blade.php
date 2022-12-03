<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-6">
            <div class="h-full flex flex-col gap-4">

                <x-exam.start.header :exam="$exam" />

                <div class=" flex flex-col gap-4 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div>
                        <h1 class="text-2xl font-bold dark:text-gray-200">{{ $exam->title }}</h1>
                        <p class="text-gray-500 dark:text-gray-400">{{ $exam->description }}</p>
                    </div>

                    <div>
                        <span class="text-2xl font-bold dark:text-gray-200">Candidates:</span>
                        @foreach ($exam->candidates as $candidate)
                            <p class="text-gray-500 dark:text-gray-400">{{ $candidate->name }}</p>
                        @endforeach
                    </div>

                    <div>
                        <span class="text-2xl font-bold dark:text-gray-200">Examiners:</span>
                        @foreach ($exam->examiners as $examiner)
                            <p class="text-gray-500 dark:text-gray-400">{{ $examiner->name }}</p>
                        @endforeach
                    </div>

                    <div>
                        <span class="text-2xl font-bold dark:text-gray-200">Invigilators:</span>
                        @foreach ($exam->invigilators as $invigilator)
                            <p class="text-gray-500 dark:text-gray-400">{{ $invigilator->name }}</p>
                        @endforeach
                    </div>

                    <div>
                        @if (!$exam->discord_voice_channel_id)
                            <a href="{{ route('exam.discord.create', $exam->id) }}">
                                CRIAR CANAIS DISCORD
                            </a>
                        @else
                            <span>CANAIS DISCORD CRIADOS</span>
                        @endif
                        <a href="{{ route('exam.start', $exam->id) }}">
                            INICIAR
                        </a>
                    </div>


                </div>

            </div>
        </div>
    </div>


</x-app-layout>
