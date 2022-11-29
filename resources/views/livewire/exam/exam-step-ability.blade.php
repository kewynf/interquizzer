<div class="text-xl text-center flex flex-col gap-1 text-gray-800 dark:text-gray-100">
    <span>{{ $ability->title }}</span>
    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $ability->description }}</span>

    @if ($ability->content_type)
        <div class="flex flex-col gap-2">
            <span>Conteúdo: {{ $ability->content_type }}</span>

            @switch($ability->content_type)
                @case('text')
                    <span class="w-full px-2 py-2 bg-slate-800 text-white rounded-md">{{ $ability->content_description }}</span>
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
        @if (!$ability->discord_message_id)
            <button wire:click="sendDiscordMessage" class="bg-green-600 text-white">
                SEND CONTENT
            </button>
        @else
            <button disabled class="bg-slate-500 text-white"> MESSAGE SENT </button>
        @endif


        @if (!$ability->answer_start_at)
            <button wire:click="startTimer" class="bg-green-500 text-white">
                START TIMER
            </button>
        @elseif(!$ability->answer_end_at)
            <button wire:click="stopTimer" class="bg-red-500 text-white">
                END TIMER
            </button>
        @else
            <button disabled="disabled" class="bg-gray-500 text-white">
                {{ $ability->answer_end_at->diffForHumans($ability->answer_start_at) }}
            </button>
            <button wire:click="resetTimer" class="bg-red-500 text-white">
                RESET TIMER
            </button>
        @endif

    </div>

    <div>
        <label for="">Grade</label>
        <input wire:model="grade" wire:blur.debounce.200ms="saveGrade" type="number" min='0' max='10'
            class="text-slate-900">
    </div>

    <div>
        <label for="">Observation</label>
        <textarea wire:model.defer="comment" wire:blur.debounce.200ms="saveComment" name="" id=""
            class="w-full h-1/2 text-slate-900"></textarea>
    </div>
    Atualização:

    {{ $ability->updated_at }}
</div>
